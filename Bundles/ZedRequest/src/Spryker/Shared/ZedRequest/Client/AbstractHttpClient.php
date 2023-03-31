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
use InvalidArgumentException;
use LogicException;
use Psr\Http\Message\RequestInterface as MessageRequestInterface;
use Psr\Http\Message\ResponseInterface as MessageResponseInterface;
use Spryker\Client\ZedRequest\Client\Request;
use Spryker\Client\ZedRequest\Client\Response as SprykerResponse;
use Spryker\Service\UtilNetwork\UtilNetworkServiceInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\ZedRequest\Client\Exception\InvalidZedResponseException;
use Spryker\Shared\ZedRequest\Client\Exception\RequestException;
use Spryker\Shared\ZedRequest\Client\HandlerStack\HandlerStackContainer;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use Throwable;

abstract class AbstractHttpClient implements HttpClientInterface
{
    /**
     * @var string
     */
    public const META_TRANSFER_ERROR =
        'Adding MetaTransfer failed. Either name missing/invalid or no object of TransferInterface provided.';

    /**
     * @deprecated not valid constant name. Use ZED_REQUEST_ERROR instead.
     *
     * @var string
     */
    public const HOST_NAME_ERROR =
        'Incorrect HOST_ZED config, expected `%s`, got `%s`. Set the URLs in your Shared/config_default_%s.php or env specific config files.';

    /**
     * @var string
     */
    public const HEADER_USER_AGENT = 'User-Agent';

    /**
     * @var string
     */
    public const HEADER_HOST_YVES = 'X-Yves-Host';

    /**
     * @var string
     */
    public const HEADER_INTERNAL_REQUEST = 'X-Internal-Request';

    /**
     * @var string
     */
    public const HEADER_HOST_ZED = 'X-Zed-Host';

    /**
     * @var string
     */
    protected const SERVER_HTTP_HOST = 'HTTP_HOST';

    /**
     * @var string
     */
    protected const SERVER_PORT = 'SERVER_PORT';

    /**
     * @var int
     */
    protected const DEFAULT_PORT = 80;

    /**
     * @var int
     */
    protected const DEFAULT_SSL_PORT = 443;

    /**
     * @var string
     */
    protected const CONFIG_FILE_PREFIX = '/config/Shared/config_';

    /**
     * @var string
     */
    protected const CONFIG_FILE_SUFFIX = '.php';

    /**
     * @var string
     */
    protected const DEFAULT_CONFIG = 'default';

    /**
     * @var string
     */
    protected const ZED_API_SSL_ENABLED = 'ZED_API_SSL_ENABLED';

    /**
     * @var string
     */
    protected const ZED_REQUEST_ERROR = 'Failed to complete request with server authority %s.
Configured with %s %s:%s in %s. Error: Stacktrace:';

    /**
     * @deprecated Will be removed with next major. Logging is done by Log bundle.
     *
     * @var string
     */
    public const EVENT_FIELD_TRANSFER_DATA = 'transfer_data';

    /**
     * @deprecated Will be removed with next major. Logging is done by Log bundle.
     *
     * @var string
     */
    public const EVENT_FIELD_TRANSFER_CLASS = 'transfer_class';

    /**
     * @deprecated Will be removed with next major. Logging is done by Log bundle.
     *
     * @var string
     */
    public const EVENT_FIELD_PATH_INFO = 'path_info';

    /**
     * @deprecated Will be removed with next major. Logging is done by Log bundle.
     *
     * @var string
     */
    public const EVENT_FIELD_SUB_TYPE = 'sub_type';

    /**
     * @deprecated Will be removed with next major. Logging is done by Log bundle.
     *
     * @var string
     */
    public const EVENT_NAME_TRANSFER_REQUEST = 'transfer_request';

    /**
     * @deprecated Will be removed with next major. Logging is done by Log bundle.
     *
     * @var string
     */
    public const EVENT_NAME_TRANSFER_RESPONSE = 'transfer_response';

    /**
     * @var string
     */
    protected const DEFAULT_XDEBUG_PROFILER_NAME = 'XDEBUG_PROFILE';

    /**
     * @var string
     */
    protected const DEFAULT_XDEBUG_SESSIOIN_NAME = 'XDEBUG_SESSION';

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
     * @var array<string, mixed>
     */
    protected $clientConfiguration;

    /**
     * @param string $baseUrl
     * @param \Spryker\Service\UtilNetwork\UtilNetworkServiceInterface $utilNetworkService
     * @param array<string, mixed> $clientConfiguration
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
     * @deprecated Use ZedRequestConstants::CLIENT_OPTIONS to change the default timeout.
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
     * @return string
     */
    protected function getConfigFilePathName(): string
    {
        return APPLICATION_ROOT_DIR .
            static::CONFIG_FILE_PREFIX . static::DEFAULT_CONFIG .
            static::CONFIG_FILE_SUFFIX;
    }

    /**
     * @return string
     */
    protected function setSslStatusMessage(): string
    {
        if (Config::get(static::ZED_API_SSL_ENABLED)) {
            return '(SSL Enabled)';
        }

        return '(SSL Disabled)';
    }

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
        ?TransferInterface $transferObject = null,
        array $metaTransfers = [],
        $requestOptions = null
    ) {
        static::$requestCounter++;

        $requestTransfer = $this->createRequestTransfer($transferObject, $metaTransfers);
        $request = $this->createGuzzleRequest($pathInfo);

        try {
            $response = $this->sendRequest($request, $requestTransfer, $requestOptions);
        } catch (GuzzleRequestException $e) {
            $message = sprintf(
                static::ZED_REQUEST_ERROR,
                $request->getUri()->getScheme() . '://' . $request->getUri()->getAuthority(),
                $this->setSslStatusMessage(),
                $request->getUri()->getHost(),
                $request->getUri()->getPort(),
                $this->getConfigFilePathName(),
            );
            $response = $e->getResponse();
            if ($response) {
                $message .= PHP_EOL . PHP_EOL . $response->getBody();
            }

            $requestException = new RequestException($message, $e->getCode(), $e);

            $this->logException($requestException);

            throw $requestException;
        }
        $responseTransfer = $this->getTransferFromResponse($response, $request);

        return $responseTransfer;
    }

    /**
     * @param \Throwable $throwable
     *
     * @return void
     */
    protected function logException(Throwable $throwable): void
    {
        ErrorLogger::getInstance()->log($throwable);
    }

    /**
     * @param string $pathInfo
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function createGuzzleRequest($pathInfo)
    {
        $headers = [
            static::HEADER_USER_AGENT => 'Yves 2.0',
            static::HEADER_HOST_YVES => 1,
            static::HEADER_INTERNAL_REQUEST => 1,
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
    protected function createRequestTransfer(?TransferInterface $transferObject = null, array $metaTransfers = [])
    {
        $request = $this->getRequest();
        $request->setSessionId((string)session_id());
        $request->setTime((string)time());
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
            $this->getClientConfiguration(),
        );

        $response = $client->send($request, $this->buildRequestOptions($requestTransfer, $requestOptions));

        if ($response->getStatusCode() !== 200 || !$response->getBody()->getSize()) {
            throw new InvalidZedResponseException('Invalid or empty response', $response, $request->getUri());
        }

        return $response;
    }

    /**
     * @param array<string, mixed> $clientConfiguration
     *
     * @return \GuzzleHttp\Client
     */
    protected function getClient(array $clientConfiguration)
    {
        $client = new Client($clientConfiguration);

        return $client;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getClientConfiguration()
    {
        $config = $this->addCookiesToForwardDebugSession($this->clientConfiguration);
        $config = $this->addCookiesToForwardDebugProfiler($config);
        $config['handler'] = $this->getHandlerStack();

        return $config;
    }

    /**
     * @param array<string, mixed> $config
     *
     * @return array<string, mixed>
     */
    protected function addCookiesToForwardDebugProfiler(array $config): array
    {
        $isProfilerForwardingEnabled = Config::get(
            ZedRequestConstants::XDEBUG_PROFILER_FORWARD_ENABLED,
            false,
        );

        if (!$isProfilerForwardingEnabled) {
            return $config;
        }

        $profilerName = Config::get(
            ZedRequestConstants::XDEBUG_PROFILER_NAME,
            static::DEFAULT_XDEBUG_PROFILER_NAME,
        );

        return $this->addCookie($config, $profilerName);
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
     * @throws \InvalidArgumentException
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

        if (is_numeric($requestOptions)) {
            return ['timeout' => $requestOptions];
        }

        throw new InvalidArgumentException(sprintf('Invalid argument given. Allowed types are "int (previous accepted $timeoutInSeconds), array" found "%s"', gettype($requestOptions)));
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
     * @param array<string, mixed> $config
     *
     * @return array<string, mixed>
     */
    protected function addCookiesToForwardDebugSession(array $config): array
    {
        $isSessionForwardingEnabled = Config::get(
            ZedRequestConstants::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED,
            false,
        );

        if (!$isSessionForwardingEnabled) {
            return $config;
        }

        $debugSessionName = Config::get(
            ZedRequestConstants::TRANSFER_DEBUG_SESSION_NAME,
            static::DEFAULT_XDEBUG_SESSIOIN_NAME,
        );

        return $this->addCookie($config, $debugSessionName);
    }

    /**
     * @param array<string, mixed> $config
     * @param string $name
     *
     * @return array<string, mixed>
     */
    protected function addCookie(array $config, string $name): array
    {
        if (!isset($_COOKIE[$name])) {
            return $config;
        }

        if (!isset($config['cookies'])) {
            $config['cookies'] = new CookieJar();
        }

        $cookie = new SetCookie();
        $cookie->setName($name);
        $cookie->setValue($_COOKIE[$name]);
        $cookie->setDomain(Config::get(ZedRequestConstants::HOST_ZED_API));

        $config['cookies']->setCookie($cookie);

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
