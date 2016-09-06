<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\Psr7\Request as Psr7Request;
use LogicException;
use Psr\Http\Message\RequestInterface as MessageRequestInterface;
use Psr\Http\Message\ResponseInterface as MessageResponseInterface;
use Spryker\Client\Auth\AuthClientInterface;
use Spryker\Client\ZedRequest\Client\Request;
use Spryker\Client\ZedRequest\Client\Response as SprykerResponse;
use Spryker\Shared\Config\Config;
use Spryker\Shared\EventJournal\EventJournalConstants;
use Spryker\Shared\EventJournal\Model\Event;
use Spryker\Shared\EventJournal\Model\SharedEventJournal;
use Spryker\Shared\Library\System;
use Spryker\Shared\Transfer\TransferInterface;
use Spryker\Shared\ZedRequest\Client\Exception\InvalidZedResponseException;
use Spryker\Shared\ZedRequest\Client\Exception\RequestException;
use Spryker\Shared\ZedRequest\Client\HandlerStack\HandlerStackContainer;
use Spryker\Shared\ZedRequest\ZedRequestConstants;

abstract class AbstractHttpClient implements HttpClientInterface
{

    const META_TRANSFER_ERROR =
        'Adding MetaTransfer failed. Either name missing/invalid or no object of TransferInterface provided.';

    const HEADER_YVES_USER_AGENT = 'User-Agent';
    const HEADER_YVES_HOST = 'X-Yves-Host';
    const HEADER_INTERNAL_REQUEST = 'X-Internal-Request';
    const HEADER_ZED_HOST = 'X-Zed-Host';

    /**
     * @deprecated Will be removed with next major. Logging is done by Log bundle.
     */
    const EVENT_FIELD_TRANSFER_DATA = 'transfer_data';

    /**
     * @deprecated Will be removed with next major. Logging is done by Log bundle.
     */
    const EVENT_FIELD_TRANSFER_CLASS = 'transfer_class';

    /**
     * @deprecated Will be removed with next major. Logging is done by Log bundle.
     */
    const EVENT_FIELD_PATH_INFO = 'path_info';

    /**
     * @deprecated Will be removed with next major. Logging is done by Log bundle.
     */
    const EVENT_FIELD_SUB_TYPE = 'sub_type';

    /**
     * @deprecated Will be removed with next major. Logging is done by Log bundle.
     */
    const EVENT_NAME_TRANSFER_REQUEST = 'transfer_request';

    /**
     * @deprecated Will be removed with next major. Logging is done by Log bundle.
     */
    const EVENT_NAME_TRANSFER_RESPONSE = 'transfer_response';

    /**
     * @var bool
     */
    protected static $alreadyRequested = false;

    /**
     * @var int
     */
    protected static $requestCounter = 0;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var int
     */
    protected static $timeoutInSeconds = 60;

    /**
     * @var \Spryker\Client\Auth\AuthClientInterface
     */
    protected $authClient;

    /**
     * @param \Spryker\Client\Auth\AuthClientInterface $authClient
     * @param string $baseUrl
     */
    public function __construct(
        AuthClientInterface $authClient,
        $baseUrl
    ) {
        $this->authClient = $authClient;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param int $timeoutInSeconds
     *
     * @return void
     */
    public static function setDefaultTimeout($timeoutInSeconds)
    {
        static::$timeoutInSeconds = $timeoutInSeconds;
    }

    /**
     * @return array
     */
    abstract public function getHeaders();

    /**
     * @param string $pathInfo
     * @param \Spryker\Shared\Transfer\TransferInterface|null $transferObject
     * @param array $metaTransfers
     * @param int|null $timeoutInSeconds
     *
     * @throws \Spryker\Shared\ZedRequest\Client\Exception\RequestException
     *
     * @return \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    public function request(
        $pathInfo,
        TransferInterface $transferObject = null,
        array $metaTransfers = [],
        $timeoutInSeconds = null
    ) {
        static::$requestCounter++;

        $requestTransfer = $this->createRequestTransfer($transferObject, $metaTransfers);
        $request = $this->createGuzzleRequest($pathInfo);

        $this->logRequest($pathInfo, $requestTransfer, (string)$request->getBody());

        try {
            $response = $this->sendRequest($request, $requestTransfer, $timeoutInSeconds);
        } catch (GuzzleRequestException $e) {
            $message = $e->getMessage();
            $response = $e->getResponse();
            if ($response) {
                $message .= PHP_EOL . PHP_EOL . $response->getBody();
            }
            $requestException = new RequestException($message, $e->getCode(), $e);

            throw $requestException;
        }
        $responseTransfer = $this->getTransferFromResponse($response, $request);

        $this->logResponse($pathInfo, $responseTransfer, (string)$response->getBody());

        return $responseTransfer;
    }

    /**
     * @param string $pathInfo
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function createGuzzleRequest($pathInfo)
    {
        $headers = [
            self::HEADER_YVES_USER_AGENT => 'Yves 2.0',
            self::HEADER_YVES_HOST => 1,
            self::HEADER_INTERNAL_REQUEST => 1,
        ];

        foreach ($this->getHeaders() as $header => $value) {
            $headers[$header] = $value;
        }

        $request = new Psr7Request('POST', $this->baseUrl . $pathInfo, $headers);

        return $request;
    }

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface|null $transferObject
     * @param array $metaTransfers
     *
     * @throws \LogicException
     *
     * @return \Spryker\Client\ZedRequest\Client\Request
     */
    protected function createRequestTransfer(TransferInterface $transferObject = null, array $metaTransfers = [])
    {
        $request = $this->getRequest();
        $request->setSessionId(session_id());
        $request->setTime(time());
        $request->setHost(System::getHostname() ?: 'n/a');

        foreach ($metaTransfers as $name => $metaTransfer) {
            if (!is_string($name) || is_numeric($name) || !$metaTransfer instanceof TransferInterface) {
                throw new LogicException(static::META_TRANSFER_ERROR);
            }
            $request->addMetaTransfer($name, $metaTransfer);
        }

        if ($transferObject) {
            $request->setTransfer($transferObject);
        }

        return $request;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Spryker\Shared\ZedRequest\Client\ObjectInterface $requestTransfer
     * @param int|null $timeoutInSeconds
     *
     * @throws \Spryker\Shared\ZedRequest\Client\Exception\InvalidZedResponseException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendRequest(MessageRequestInterface $request, ObjectInterface $requestTransfer, $timeoutInSeconds = null)
    {
        $handlerStackContainer = new HandlerStackContainer();
        $config = [
            'timeout' => ($timeoutInSeconds ?: static::$timeoutInSeconds),
            'connect_timeout' => 1.5,
            'handler' => $handlerStackContainer->getHandlerStack()
        ];
        $config = $this->addCookiesToForwardDebugSession($config);
        $client = new Client($config);

        $options = [
            'json' => $requestTransfer->toArray()
        ];
        $response = $client->send($request, $options);

        if (!$response || $response->getStatusCode() !== 200 || !$response->getBody()->getSize()) {
            throw new InvalidZedResponseException('Invalid or empty response', $response, $request->getUri());
        }

        return $response;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @throws \Spryker\Shared\ZedRequest\Client\Exception\InvalidZedResponseException
     *
     * @return \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    protected function getTransferFromResponse(MessageResponseInterface $response, MessageRequestInterface $request)
    {
        $data = json_decode(trim($response->getBody()), true);
        if (!$data || !is_array($data)) {
            throw new InvalidZedResponseException('Invalid JSON', $response, $request->getUri());
        }
        $responseTransfer = new SprykerResponse();
        $responseTransfer->fromArray($data);

        return $responseTransfer;
    }

    /**
     * @deprecated Requests logged within GuzzleLogServiceProvider() add this one to your Bootstrap.
     *
     * @param string $pathInfo
     * @param \Spryker\Shared\ZedRequest\Client\EmbeddedTransferInterface $requestTransfer
     * @param string $rawBody
     *
     * @return void
     */
    protected function logRequest($pathInfo, EmbeddedTransferInterface $requestTransfer, $rawBody)
    {
        $this->doLog($pathInfo, static::EVENT_NAME_TRANSFER_REQUEST, $requestTransfer, $rawBody);
    }

    /**
     * @deprecated Response logged within GuzzleLogServiceProvider() add this one to your Bootstrap.
     *
     * @param string $pathInfo
     * @param \Spryker\Shared\ZedRequest\Client\EmbeddedTransferInterface $responseTransfer
     * @param string $rawBody
     *
     * @return void
     */
    protected function logResponse($pathInfo, EmbeddedTransferInterface $responseTransfer, $rawBody)
    {
        $this->doLog($pathInfo, static::EVENT_NAME_TRANSFER_RESPONSE, $responseTransfer, $rawBody);
    }

    /**
     * @deprecated This method was used by logRequest() and logResponse(). You can also use the LoggerTrait.
     *
     * @param string $pathInfo
     * @param string $subType
     * @param \Spryker\Shared\ZedRequest\Client\EmbeddedTransferInterface $transfer
     * @param string $rawBody
     *
     * @return void
     */
    protected function doLog($pathInfo, $subType, EmbeddedTransferInterface $transfer, $rawBody)
    {
        if (Config::get(EventJournalConstants::DISABLE_EVENT_JOURNAL, false)) {
            return;
        }

        $eventJournal = new SharedEventJournal();
        $event = new Event();
        $responseTransfer = $transfer->getTransfer();
        if ($responseTransfer instanceof TransferInterface) {
            $event->setField(static::EVENT_FIELD_TRANSFER_DATA, $responseTransfer->modifiedToArray(true));
            $event->setField(static::EVENT_FIELD_TRANSFER_CLASS, get_class($responseTransfer));
        } else {
            $event->setField(static::EVENT_FIELD_TRANSFER_DATA, null);
            $event->setField(static::EVENT_FIELD_TRANSFER_CLASS, null);
        }
        $event->setField(Event::FIELD_NAME, 'transfer');
        $event->setField(static::EVENT_FIELD_PATH_INFO, $pathInfo);
        $event->setField(static::EVENT_FIELD_SUB_TYPE, $subType);
        $eventJournal->saveEvent($event);
    }

    /**
     * Used for debug output
     *
     * @return int
     */
    public static function getRequestCounter()
    {
        return static::$requestCounter;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    protected function addCookiesToForwardDebugSession(array $config)
    {
        if (!Config::get(ZedRequestConstants::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED)) {
            return $config;
        }

        if (!isset($_COOKIE[Config::get(ZedRequestConstants::TRANSFER_DEBUG_SESSION_NAME)])) {
            return $config;
        }

        $cookie = new SetCookie();
        $cookie->setName(trim(Config::get(ZedRequestConstants::TRANSFER_DEBUG_SESSION_NAME)));
        $cookie->setValue($_COOKIE[Config::get(ZedRequestConstants::TRANSFER_DEBUG_SESSION_NAME)]);
        $cookie->setDomain(Config::get(ZedRequestConstants::HOST_ZED_API));

        $cookieJar = new CookieJar();
        $cookieJar->setCookie($cookie);

        $config['cookies'] = $cookieJar;

        return $config;
    }

    /**
     * @return \Spryker\Client\ZedRequest\Client\Request
     */
    private function getRequest()
    {
        $request = new Request();

        return $request;
    }

}
