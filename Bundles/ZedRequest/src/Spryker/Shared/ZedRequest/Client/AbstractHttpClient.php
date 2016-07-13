<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException as GuzzleRequestException;
use Guzzle\Http\Message\EntityEnclosingRequest;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Cookie\Cookie;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Spryker\Client\Auth\AuthClientInterface;
use Spryker\Client\ZedRequest\Client\Request;
use Spryker\Client\ZedRequest\Client\Response as SprykerResponse;
use Spryker\Shared\Config\Config;
use Spryker\Shared\EventJournal\Model\Event;
use Spryker\Shared\EventJournal\Model\SharedEventJournal;
use Spryker\Shared\Library\System;
use Spryker\Shared\Transfer\TransferInterface;
use Spryker\Shared\ZedRequest\Client\Exception\InvalidZedResponseException;
use Spryker\Shared\ZedRequest\Client\Exception\RequestException;
use Spryker\Shared\ZedRequest\Client\ResponseInterface as ZedResponse;
use Spryker\Shared\ZedRequest\ZedRequestConstants;

abstract class AbstractHttpClient implements HttpClientInterface
{

    const META_TRANSFER_ERROR =
        'Adding MetaTransfer failed. Either name missing/invalid or no object of TransferInterface provided.';

    const EVENT_FIELD_TRANSFER_DATA = 'transfer_data';

    const EVENT_FIELD_TRANSFER_CLASS = 'transfer_class';

    const EVENT_FIELD_PATH_INFO = 'path_info';

    const EVENT_FIELD_SUB_TYPE = 'sub_type';

    const EVENT_NAME_TRANSFER_REQUEST = 'transfer_request';

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
     *
     * @todo Add ths timeout to config so this could be edited easily and from configuration level #894
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
        self::$timeoutInSeconds = $timeoutInSeconds;
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

        self::$requestCounter++;

        $requestTransfer = $this->createRequestTransfer($transferObject, $metaTransfers);
        $request = $this->createGuzzleRequest($pathInfo, $requestTransfer, $timeoutInSeconds);
        $this->logRequest($pathInfo, $requestTransfer, (string)$request->getBody());

        $this->forwardDebugSession($request);
        try {
            $response = $this->sendRequest($request);
        } catch (GuzzleRequestException $e) {
            $requestException = new RequestException($e->getMessage(), $e->getCode(), $e);
            $requestException->setExtra((string)$e->getRequest()->getResponse());

            throw $requestException;
        }
        $responseTransfer = $this->getTransferFromResponse($response);
        $this->logResponse($pathInfo, $responseTransfer, $response->getBody(true));

        return $responseTransfer;
    }

    /**
     * @param string $pathInfo
     *
     * @return bool
     */
    protected function isLoggingAllowed($pathInfo)
    {
        return strpos($pathInfo, 'heartbeat');
    }

    /**
     * @param string $pathInfo
     * @param \Spryker\Shared\ZedRequest\Client\RequestInterface $requestTransfer
     * @param int|null $timeoutInSeconds
     *
     * @return \Guzzle\Http\Message\EntityEnclosingRequest
     */
    protected function createGuzzleRequest($pathInfo, RequestInterface $requestTransfer, $timeoutInSeconds = null)
    {
        $client = new Client(
            $this->baseUrl,
            [
                Client::REQUEST_OPTIONS => [
                    'timeout' => ($timeoutInSeconds ?: self::$timeoutInSeconds),
                    'connect_timeout' => 1.5,
                ],
            ]
        );

        $char = (strpos($pathInfo, '?') === false) ? '?' : ' &';
        /*
         * @todo CD-417
         */
        $eventJournal = new SharedEventJournal();
        $event = new Event();
        $eventJournal->applyCollectors($event);
        $requestId = $event->getFields()['request_id'];
        $pathInfo .= $char . 'yvesRequestId=' . $requestId;

        $client->setUserAgent('Yves 2.0');
        /** @var \Guzzle\Http\Message\EntityEnclosingRequest $request */
        $request = $client->post($pathInfo);
        $request->addHeader('X-Yves-Host', 1);
        $request->addHeader('X-Internal-Request', 1);
        foreach ($this->getHeaders() as $header => $value) {
            $request->addHeader($header, $value);
        }

        $data = $requestTransfer->toArray(true);
//        unset($data['transfer']);
        $rawRequestBody = json_encode($data);
        $request->setBody($rawRequestBody, 'application/json');
//        $request->setHeader('Host', System::getHostname());

        return $request;
    }

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $transferObject
     * @param array $metaTransfers
     *
     * @throws \LogicException
     *
     * @return \Spryker\Shared\ZedRequest\Client\AbstractRequest
     */
    protected function createRequestTransfer(TransferInterface $transferObject, array $metaTransfers)
    {
        $request = $this->getRequest();
        $request->setSessionId(session_id());
        $request->setTime(time());
        $request->setHost(System::getHostname() ?: 'n/a');

        foreach ($metaTransfers as $name => $metaTransfer) {
            if (!is_string($name) || is_numeric($name) || !$metaTransfer instanceof TransferInterface) {
                throw new \LogicException(self::META_TRANSFER_ERROR);
            }
            $request->addMetaTransfer($name, $metaTransfer);
        }

        if (!empty($transferObject)) {
            $request->setTransfer($transferObject);
        }

        return $request;
    }

    /**
     * @param \Guzzle\Http\Message\EntityEnclosingRequest $request
     *
     * @throws \Spryker\Shared\ZedRequest\Client\Exception\InvalidZedResponseException
     *
     * @return \Guzzle\Http\Message\Response
     */
    protected function sendRequest(EntityEnclosingRequest $request)
    {
        $response = $request->send();
        if (!$response || !$response->isSuccessful() || !$response->getBody()->getSize()) {
            throw new InvalidZedResponseException('empty', $response);
        }

        return $response;
    }

    /**
     * @param \Guzzle\Http\Message\Response $response
     *
     * @throws \Spryker\Shared\ZedRequest\Client\Exception\InvalidZedResponseException
     *
     * @return \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    protected function getTransferFromResponse(Response $response)
    {
        $data = json_decode(trim($response->getBody(true)), true);
        if (empty($data) || !is_array($data)) {
            throw new InvalidZedResponseException('no valid JSON', $response);
        }
        $responseTransfer = new SprykerResponse();
        $responseTransfer->fromArray($data);

        return $responseTransfer;
    }

    /**
     * @param string $pathInfo
     * @param \Spryker\Shared\ZedRequest\Client\RequestInterface $requestTransfer
     * @param string $rawBody
     *
     * @return void
     */
    protected function logRequest($pathInfo, RequestInterface $requestTransfer, $rawBody)
    {
        $this->doLog($pathInfo, self::EVENT_NAME_TRANSFER_REQUEST, $requestTransfer, $rawBody);
    }

    /**
     * @param string $pathInfo
     * @param \Spryker\Shared\ZedRequest\Client\ResponseInterface $responseTransfer
     * @param string $rawBody
     *
     * @return void
     */
    protected function logResponse($pathInfo, ZedResponse $responseTransfer, $rawBody)
    {
        $this->doLog($pathInfo, self::EVENT_NAME_TRANSFER_RESPONSE, $responseTransfer, $rawBody);
    }

    /**
     * @param string $pathInfo
     * @param string $subType
     * @param \Spryker\Shared\ZedRequest\Client\ObjectInterface $transfer
     * @param string $rawBody
     *
     * @return void
     */
    protected function doLog($pathInfo, $subType, ObjectInterface $transfer, $rawBody)
    {
        $eventJournal = new SharedEventJournal();
        $event = new Event();
        $responseTransfer = $transfer->getTransfer();
        if ($responseTransfer instanceof TransferInterface) {
            $event->setField(self::EVENT_FIELD_TRANSFER_DATA, $responseTransfer->modifiedToArray(true));
            $event->setField(self::EVENT_FIELD_TRANSFER_CLASS, get_class($responseTransfer));
        } else {
            $event->setField(self::EVENT_FIELD_TRANSFER_DATA, null);
            $event->setField(self::EVENT_FIELD_TRANSFER_CLASS, null);
        }

        $event->setField(Event::FIELD_NAME, 'transfer');
        $event->setField(self::EVENT_FIELD_PATH_INFO, $pathInfo);
        $event->setField(self::EVENT_FIELD_SUB_TYPE, $subType);

        $eventJournal->saveEvent($event);
    }

    /**
     * Used for debug output
     *
     * @return int
     */
    public static function getRequestCounter()
    {
        return self::$requestCounter;
    }

    /**
     * @param \Guzzle\Http\Message\EntityEnclosingRequest $request
     *
     * @return void
     */
    protected function forwardDebugSession(EntityEnclosingRequest $request)
    {
        if (Config::get(ZedRequestConstants::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED)) {
            if (isset($_COOKIE[Config::get(ZedRequestConstants::TRANSFER_DEBUG_SESSION_NAME)])) {
                $cookie = new Cookie();
                $cookie->setName(trim(Config::get(ZedRequestConstants::TRANSFER_DEBUG_SESSION_NAME)));
                $cookie->setValue($_COOKIE[Config::get(ZedRequestConstants::TRANSFER_DEBUG_SESSION_NAME)]);
                $cookie->setDomain(Config::get(ZedRequestConstants::HOST_ZED_API));
                $cookieArray = new ArrayCookieJar(true);
                $cookieArray->add($cookie);

                $request->addSubscriber(new CookiePlugin($cookieArray));
            }
        }
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
