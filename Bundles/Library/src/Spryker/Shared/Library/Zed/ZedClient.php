<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Zed;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Spryker\Client\EventJournal\EventJournal;
use Spryker\Client\EventJournal\EventJournalClient;
use Spryker\Shared\Config\Config;
use Spryker\Shared\EventJournal\Model\Event;
use Spryker\Shared\Library\Communication\ObjectInterface;
use Spryker\Shared\Library\Communication\Request;
use Spryker\Shared\Library\Communication\Response as CommunicationResponse;
use Spryker\Shared\Library\LibraryConstants;
use Spryker\Shared\Library\System;
use Spryker\Shared\Transfer\TransferInterface;
use Spryker\Shared\ZedRequest\Client\EmbeddedTransferInterface;
use Spryker\Shared\ZedRequest\Client\Exception\InvalidZedResponseException;

/**
 * @deprecated Moved to ZedRequest Bundle
 */
class ZedClient
{

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
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var int in seconds
     */
    protected static $timeoutInSeconds = 10;

    /**
     * @param string $baseUrl
     * @param string|null $username
     * @param string|null $password
     */
    public function __construct($baseUrl, $username = null, $password = null)
    {
        $this->baseUrl = $baseUrl;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @deprecated Moved to ZedRequest Bundle
     *
     * @param int $timeoutInSeconds
     *
     * @return void
     */
    public static function setDefaultTimeout($timeoutInSeconds)
    {
        self::$timeoutInSeconds = $timeoutInSeconds;
    }

    /**
     * @deprecated Moved to ZedRequest Bundle
     *
     * @param string $pathInfo
     * @param \Spryker\Shared\Transfer\TransferInterface|null $transferObject
     * @param array $metaTransfers
     * @param int|null $timeoutInSeconds
     * @param bool $isBackgroundRequest
     *
     * @throws \LogicException
     *
     * @return \Spryker\Shared\Library\Communication\Response
     */
    public function request($pathInfo, TransferInterface $transferObject = null, array $metaTransfers = [], $timeoutInSeconds = null, $isBackgroundRequest = false)
    {
        if (!$this->isRequestAllowed($isBackgroundRequest)) {
            throw new \LogicException('You cannot make more than one request from Yves to Zed.');
        }
        self::$requestCounter++;

        $requestTransfer = $this->createRequestTransfer($transferObject, $metaTransfers);
        $request = $this->createGuzzleRequest($pathInfo);
        $this->logRequest($pathInfo, $requestTransfer, $request->getBody());

        $response = $this->sendRequest($request, $requestTransfer, $timeoutInSeconds);
        $responseTransfer = $this->getTransferFromResponse($response);
        $this->logResponse($pathInfo, $responseTransfer, $response->getBody());

        return $responseTransfer;
    }

    /**
     * @param string $pathInfo
     *
     * @return bool
     */
    protected function isLoggingAllowed($pathInfo)
    {
        return mb_strpos($pathInfo, 'heartbeat') !== false;
    }

    /**
     * @param bool $isBackgroundRequest
     *
     * @return bool
     */
    protected function isRequestAllowed($isBackgroundRequest)
    {
        if (!$isBackgroundRequest) {
            if (self::$alreadyRequested === true) {
                return false;
            }
            self::$alreadyRequested = true;
        }

        return true;
    }

    /**
     * @param string $pathInfo
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function createGuzzleRequest($pathInfo)
    {
        $char = (strpos($pathInfo, '?') === false) ? '?' : '&';

        $eventJournal = new EventJournal();
        $event = new Event();
        $eventJournal->applyCollectors($event);
        $requestId = $event->getFields()['request_id'];
        $pathInfo .= $char . 'yvesRequestId=' . $requestId;

        $headers = [
            'User-Agent' => 'Yves 2.0',
            'X-Yves-Host' => 1
        ];
        $request = new \GuzzleHttp\Psr7\Request('POST', $this->baseUrl . $pathInfo, $headers);

        return $request;
    }

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $transferObject
     * @param array $metaTransfers
     *
     * @throws \LogicException
     *
     * @return \Spryker\Shared\Library\Communication\Request
     */
    protected function createRequestTransfer(TransferInterface $transferObject, array $metaTransfers)
    {
        $request = new Request();
        $request->setSessionId(session_id());
        $request->setTime(time());
        $request->setHost(System::getHostname() ?: 'n/a');

        foreach ($metaTransfers as $name => $metaTransfer) {
            if (!is_string($name) || is_numeric($name) || !$metaTransfer instanceof TransferInterface) {
                throw new \LogicException('Adding MetaTransfer failed. Either name missing/invalid or no object of TransferInterface provided.');
            }
            $request->addMetaTransfer($name, $metaTransfer);
        }
        if ($this->username) {
            $request->setUsername($this->username);
        }
        if ($this->password) {
            $request->setPassword($this->password);
        }
        if ($transferObject) {
            $request->setTransfer($transferObject);
        }

        return $request;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Spryker\Shared\Library\Communication\ObjectInterface $requestTransfer
     * @param int|null $timeoutInSeconds

     * @throws \Spryker\Shared\ZedRequest\Client\Exception\InvalidZedResponseException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendRequest(RequestInterface $request, ObjectInterface $requestTransfer, $timeoutInSeconds = null)
    {
        $config = [
            'timeout' => ($timeoutInSeconds ?: static::$timeoutInSeconds),
            'connect_timeout' => 1.5,
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
     *
     * @throws \Spryker\Shared\ZedRequest\Client\Exception\InvalidZedResponseException
     *
     * @return \Spryker\Shared\Library\Communication\Response
     */
    protected function getTransferFromResponse(ResponseInterface $response)
    {
        $data = json_decode(trim($response->getBody()), true);
        if (!$data || !is_array($data)) {
            throw new InvalidZedResponseException('no valid JSON', $response, '');
        }

        $responseTransfer = new CommunicationResponse();
        $responseTransfer->fromArray($data);

        return $responseTransfer;
    }

    /**
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
     * @param string $pathInfo
     * @param string $subType
     * @param \Spryker\Shared\ZedRequest\Client\EmbeddedTransferInterface $transfer
     * @param string $rawBody
     *
     * @return void
     */
    protected function doLog($pathInfo, $subType, EmbeddedTransferInterface $transfer, $rawBody)
    {
        $eventJournalClient = new EventJournalClient();
        $event = new Event();
        $responseTransfer = $transfer->getTransfer();
        if ($responseTransfer instanceof TransferInterface) {
            $event->setField('transfer_data', $responseTransfer->toArray());
            $event->setField('transfer_class', get_class($responseTransfer));
        } else {
            $event->setField('transfer_data', null);
            $event->setField('transfer_class', null);
        }
        $event->setField('raw_body', $rawBody);

        $event->setField('name', 'transfer');
        $event->setField('path_info', $pathInfo);
        $event->setField('sub_type', $subType);
        $eventJournalClient->saveEvent($event);
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
     * @param array $config
     *
     * @return array
     */
    protected function addCookiesToForwardDebugSession(array $config)
    {
        if (!Config::get(LibraryConstants::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED)) {
            return $config;
        }

        $cookie = new SetCookie();
        $cookie->setName(trim(Config::get(LibraryConstants::TRANSFER_DEBUG_SESSION_NAME)));
        $cookie->setValue($_COOKIE[Config::get(LibraryConstants::TRANSFER_DEBUG_SESSION_NAME)]);
        $cookie->setDomain(Config::get(LibraryConstants::HOST_ZED_API));

        $cookieJar = new CookieJar();
        $cookieJar->setCookie($cookie);

        $config['cookies'] = $cookieJar;

        return $config;
    }

}
