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
use Spryker\Client\ZedRequest\Client\Request;
use Spryker\Client\ZedRequest\Client\Response as SprykerResponse;
use Spryker\Service\UtilNetwork\UtilNetworkServiceInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\ZedRequest\Client\Exception\InvalidRequestOptionException;
use Spryker\Shared\ZedRequest\Client\Exception\InvalidZedResponseException;
use Spryker\Shared\ZedRequest\Client\Exception\RequestException;
use Spryker\Shared\ZedRequest\Client\HandlerStack\HandlerStackContainer;
use Spryker\Shared\ZedRequest\ZedRequestConstants;

abstract class AbstractHttpClient implements HttpClientInterface
{
    const META_TRANSFER_ERROR =
        'Adding MetaTransfer failed. Either name missing/invalid or no object of TransferInterface provided.';

    const HEADER_USER_AGENT = 'User-Agent';
    const HEADER_HOST_YVES = 'X-Yves-Host';
    const HEADER_INTERNAL_REQUEST = 'X-Internal-Request';
    const HEADER_HOST_ZED = 'X-Zed-Host';

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
     * @var \Spryker\Service\UtilNetwork\UtilNetworkServiceInterface
     */
    protected $utilNetworkService;

    /**
     * @var array
     */
    protected $clientConfiguration;

    /**
     * @param string $baseUrl
     * @param \Spryker\Service\UtilNetwork\UtilNetworkServiceInterface $utilNetworkService
     * @param array $clientConfiguration
     */
    public function __construct(
        $baseUrl,
        UtilNetworkServiceInterface $utilNetworkService,
        array $clientConfiguration = []
    ) {
        $this->baseUrl = $baseUrl;
        $this->utilNetworkService = $utilNetworkService;
        $this->clientConfiguration = $clientConfiguration;
    }

    /**
     * @deprecated Please use ZedRequestConstants::CLIENT_OPTIONS to change the default timeout.
     *
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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $transferObject
     * @param array $metaTransfers
     * @param array|int|null $requestOptions
     *
     * @throws \Spryker\Shared\ZedRequest\Client\Exception\RequestException
     *
     * @return \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    public function request(
        $pathInfo,
        TransferInterface $transferObject = null,
        array $metaTransfers = [],
        $requestOptions = null
    ) {
        static::$requestCounter++;

        $requestTransfer = $this->createRequestTransfer($transferObject, $metaTransfers);
        $request = $this->createGuzzleRequest($pathInfo);

        try {
            $response = $this->sendRequest($request, $requestTransfer, $requestOptions);
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
            self::HEADER_USER_AGENT => 'Yves 2.0',
            self::HEADER_HOST_YVES => 1,
            self::HEADER_INTERNAL_REQUEST => 1,
        ];

        foreach ($this->getHeaders() as $header => $value) {
            $headers[$header] = $value;
        }

        $request = new Psr7Request('POST', $this->baseUrl . $pathInfo, $headers);

        return $request;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $transferObject
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
        $request->setHost($this->utilNetworkService->getHostName() ?: 'n/a');

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
     * @param array|int|null $requestOptions
     *
     * @throws \Spryker\Shared\ZedRequest\Client\Exception\InvalidZedResponseException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendRequest(MessageRequestInterface $request, ObjectInterface $requestTransfer, $requestOptions = null)
    {
        $client = $this->getClient(
            $this->getClientConfiguration()
        );

        $response = $client->send($request, $this->buildRequestOptions($requestTransfer, $requestOptions));

        if (!$response || $response->getStatusCode() !== 200 || !$response->getBody()->getSize()) {
            throw new InvalidZedResponseException('Invalid or empty response', $response, $request->getUri());
        }

        return $response;
    }

    /**
     * @param array $clientConfiguration
     *
     * @return \GuzzleHttp\Client
     */
    protected function getClient(array $clientConfiguration)
    {
        $client = new Client($clientConfiguration);

        return $client;
    }

    /**
     * @return array
     */
    protected function getClientConfiguration()
    {
        $config = $this->addCookiesToForwardDebugSession($this->clientConfiguration);
        $config['handler'] = $this->getHandlerStack();

        return $config;
    }

    /**
     * @return \GuzzleHttp\HandlerStack
     */
    protected function getHandlerStack()
    {
        $handlerStackContainer = new HandlerStackContainer();

        return $handlerStackContainer->getHandlerStack();
    }

    /**
     * @param \Spryker\Shared\ZedRequest\Client\ObjectInterface $requestTransfer
     * @param array|int|null $requestOptions
     *
     * @return array
     */
    protected function buildRequestOptions(ObjectInterface $requestTransfer, $requestOptions = null)
    {
        $normalizedRequestOptions = $this->normalizeRequestOptions($requestOptions);
        $requestOptions = [
            'json' => $requestTransfer->toArray(),
            'allow_redirects' => [
                'strict' => true,
            ],
        ];

        $requestOptions = array_merge($requestOptions, $normalizedRequestOptions);

        return $requestOptions;
    }

    /**
     * @deprecated Added for BC reasons. Previously an integer was used for timeout settings. We now allow an array to pass more options to the request.
     *
     * @param array|int|null $requestOptions
     *
     * @throws \Spryker\Shared\ZedRequest\Client\Exception\InvalidRequestOptionException
     *
     * @return array
     */
    protected function normalizeRequestOptions($requestOptions = null)
    {
        if (is_array($requestOptions)) {
            return $requestOptions;
        }

        if ($requestOptions === null) {
            return [];
        }

        if (is_int($requestOptions)) {
            return ['timeout' => $requestOptions];
        }

        throw new InvalidRequestOptionException(sprintf('Invalid argument given. Allowed types are "int (previous accepted $timeoutInSeconds), array" found "%s"', gettype($requestOptions)));
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
        $isSessionForwardingEnabled = Config::get(ZedRequestConstants::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED, false);

        if (!$isSessionForwardingEnabled) {
            return $config;
        }

        $debugSessionName = Config::get(ZedRequestConstants::TRANSFER_DEBUG_SESSION_NAME, 'XDEBUG_SESSION');
        if (!isset($_COOKIE[$debugSessionName])) {
            return $config;
        }

        $cookie = new SetCookie();
        $cookie->setName($debugSessionName);
        $cookie->setValue($_COOKIE[$debugSessionName]);
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
