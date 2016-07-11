<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Zed;

use Guzzle\Http\Client;
use Guzzle\Http\Message\EntityEnclosingRequest;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Cookie\Cookie;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Spryker\Client\EventJournal\EventJournal;
use Spryker\Client\EventJournal\EventJournalClient;
use Spryker\Shared\Config\Config;
use Spryker\Shared\EventJournal\Model\Event;
use Spryker\Shared\Library\Communication\ObjectInterface;
use Spryker\Shared\Library\Communication\Request;
use Spryker\Shared\Library\Communication\Response as CommunicationResponse;
use Spryker\Shared\Library\LibraryConstants;
use Spryker\Shared\Library\System;
use Spryker\Shared\Library\Zed\Exception\InvalidZedResponseException;
use Spryker\Shared\Transfer\TransferInterface;

class ZedClient
{

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
     * @param int $timeoutInSeconds
     *
     * @return void
     */
    public static function setDefaultTimeout($timeoutInSeconds)
    {
        self::$timeoutInSeconds = $timeoutInSeconds;
    }

    /**
     * @param string $pathInfo
     * @param \Spryker\Shared\Transfer\TransferInterface|null $transferObject
     * @param array $metaTransfers
     * @param int|null $timeoutInSeconds
     *
     * @throws \LogicException
     *
     * @return \Spryker\Shared\Library\Communication\Response
     */
    public function request($pathInfo, TransferInterface $transferObject = null, array $metaTransfers = [], $timeoutInSeconds = null)
    {
        self::$requestCounter++;

        $requestTransfer = $this->createRequestTransfer($transferObject, $metaTransfers);
        $request = $this->createGuzzleRequest($pathInfo, $requestTransfer, $timeoutInSeconds);
        $this->logRequest($pathInfo, $requestTransfer, $request->getBody());

        $this->forwardDebugSession($request);
        $response = $this->sendRequest($request);
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
        return mb_strpos($pathInfo, 'heartbeat') !== false;
    }

     /**
     * @param string $pathInfo
     * @param \Spryker\Shared\Library\Communication\Request $requestTransfer
     * @param int|null $timeoutInSeconds
     *
     * @return \Guzzle\Http\Message\EntityEnclosingRequest
     */
    protected function createGuzzleRequest($pathInfo, Request $requestTransfer, $timeoutInSeconds = null)
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

        $char = (strpos($pathInfo, '?') === false) ? '?' : '&';
        /*
         * @todo CD-417
         */
        $eventJournal = new EventJournal();
        $event = new Event();
        $eventJournal->applyCollectors($event);
        $requestId = $event->getFields()['request_id'];
        $pathInfo .= $char . 'yvesRequestId=' . $requestId;

        $client->setUserAgent('Yves 2.0');
        /** @var \Guzzle\Http\Message\EntityEnclosingRequest $request */
        $request = $client->post($pathInfo);
        $request->addHeader('X-Yves-Host', 1);

        $rawRequestBody = json_encode($requestTransfer->toArray());

        $request->setBody($rawRequestBody, 'application/json');
        //$request->setHeader('Host', System::getHostname());

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
     * @param \Guzzle\Http\Message\EntityEnclosingRequest $request
     *
     * @throws Exception\InvalidZedResponseException
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
     * @throws Exception\InvalidZedResponseException
     *
     * @return \Spryker\Shared\Library\Communication\Response
     */
    protected function getTransferFromResponse(Response $response)
    {
        $data = json_decode(trim($response->getBody(true)), true);
        if (empty($data) || !is_array($data)) {
            throw new InvalidZedResponseException('no valid JSON', $response);
        }

        $responseTransfer = new CommunicationResponse();
        $responseTransfer->fromArray($data);

        return $responseTransfer;
    }

    /**
     * @param string $pathInfo
     * @param \Spryker\Shared\Library\Communication\Request $requestTransfer
     * @param string $rawBody
     *
     * @return void
     */
    protected function logRequest($pathInfo, Request $requestTransfer, $rawBody)
    {
        $this->doLog($pathInfo, Types::TRANSFER_REQUEST, $requestTransfer, $rawBody);
    }

    /**
     * @param string $pathInfo
     * @param \Guzzle\Http\Message\Response $responseTransfer
     * @param string $rawBody
     *
     * @return void
     */
    protected function logResponse($pathInfo, Response $responseTransfer, $rawBody)
    {
        $this->doLog($pathInfo, Types::TRANSFER_RESPONSE, $responseTransfer, $rawBody);
    }

    /**
     * @param string $pathInfo
     * @param string $subType
     * @param \Spryker\Shared\Library\Communication\ObjectInterface $transfer
     * @param string $rawBody
     *
     * @return void
     */
    protected function doLog($pathInfo, $subType, ObjectInterface $transfer, $rawBody)
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
     * @param \Guzzle\Http\Message\EntityEnclosingRequest $request
     *
     * @return void
     */
    protected function forwardDebugSession(EntityEnclosingRequest $request)
    {
        if (Config::get(LibraryConstants::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED)) {
            $cookie = new Cookie();
            $cookie->setName(trim(Config::get(LibraryConstants::TRANSFER_DEBUG_SESSION_NAME)));
            $cookie->setValue($_COOKIE[Config::get(LibraryConstants::TRANSFER_DEBUG_SESSION_NAME)]);
            $cookie->setDomain(Config::get(LibraryConstants::HOST_ZED_API));
            $cookieArray = new ArrayCookieJar(true);
            $cookieArray->add($cookie);

            $request->addSubscriber(new CookiePlugin($cookieArray));
        }
    }

}
