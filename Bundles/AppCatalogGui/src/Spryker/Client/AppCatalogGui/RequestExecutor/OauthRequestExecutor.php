<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AppCatalogGui\RequestExecutor;

use Generated\Shared\Transfer\AccessTokenErrorTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use GuzzleHttp\RequestOptions;
use Spryker\Client\AppCatalogGui\AppCatalogGuiConfig;
use Spryker\Client\AppCatalogGui\Dependency\External\AppCatalogGuiToHttpClientAdapterInterface;
use Spryker\Client\AppCatalogGui\Dependency\Service\AppCatalogGuiToUtilEncodingServiceInterface;
use Spryker\Client\AppCatalogGui\Exception\ExternalHttpRequestException;
use Symfony\Component\HttpFoundation\Request;

class OauthRequestExecutor implements OauthRequestExecutorInterface
{
    /**
     * @var string
     */
    protected const GRANT_TYPE = 'client_credentials';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_ERROR = 'error';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_ERROR_DESCRIPTION = 'error_description';

    /**
     * @var \Spryker\Client\AppCatalogGui\Dependency\External\AppCatalogGuiToHttpClientAdapterInterface
     */
    protected $appCatalogGuiToHttpClientAdapter;

    /**
     * @var \Spryker\Client\AppCatalogGui\Dependency\Service\AppCatalogGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Client\AppCatalogGui\AppCatalogGuiConfig
     */
    protected $appCatalogGuiConfig;

    /**
     * @param \Spryker\Client\AppCatalogGui\Dependency\External\AppCatalogGuiToHttpClientAdapterInterface $appCatalogGuiToHttpClientAdapter
     * @param \Spryker\Client\AppCatalogGui\Dependency\Service\AppCatalogGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\AppCatalogGui\AppCatalogGuiConfig $appCatalogGuiConfig
     */
    public function __construct(
        AppCatalogGuiToHttpClientAdapterInterface $appCatalogGuiToHttpClientAdapter,
        AppCatalogGuiToUtilEncodingServiceInterface $utilEncodingService,
        AppCatalogGuiConfig $appCatalogGuiConfig
    ) {
        $this->appCatalogGuiToHttpClientAdapter = $appCatalogGuiToHttpClientAdapter;
        $this->utilEncodingService = $utilEncodingService;
        $this->appCatalogGuiConfig = $appCatalogGuiConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function requestAccessToken(): AccessTokenResponseTransfer
    {
        $aopIdpUrl = $this->appCatalogGuiConfig->getAopIdpUrl();

        if (!$aopIdpUrl) {
            $oauthResponseErrorTransfer = (new AccessTokenErrorTransfer())
                ->setError('Aop IDP url was not found.');

            return (new AccessTokenResponseTransfer())
                ->setAccessTokenError($oauthResponseErrorTransfer)
                ->setIsSuccessful(false);
        }

        try {
            $response = $this->appCatalogGuiToHttpClientAdapter->request(
                Request::METHOD_POST,
                $aopIdpUrl,
                [
                    RequestOptions::JSON => [
                        'client_id' => $this->appCatalogGuiConfig->getAopClientId(),
                        'client_secret' => $this->appCatalogGuiConfig->getAopClientSecret(),
                        'grant_type' => static::GRANT_TYPE,
                        'audience' => $this->appCatalogGuiConfig->getAopAudience(),
                    ],
                ],
            );

            /** @var array<string, mixed>|null $responseData */
            $responseData = $this->utilEncodingService->decodeJson($response->getBody()->getContents(), true);

            if ($responseData === null) {
                $oauthResponseErrorTransfer = (new AccessTokenErrorTransfer())
                    ->setError('Response is not valid.');

                return (new AccessTokenResponseTransfer())
                    ->setIsSuccessful(false)
                    ->setAccessTokenError($oauthResponseErrorTransfer);
            }

            return (new AccessTokenResponseTransfer())
                ->setIsSuccessful(true)
                ->fromArray($responseData, true);
        } catch (ExternalHttpRequestException $requestException) {
            return $this->processUnexpectedResponse($requestException);
        }
    }

    /**
     * @param \Spryker\Client\AppCatalogGui\Exception\ExternalHttpRequestException $externalHttpRequestException
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    protected function processUnexpectedResponse(ExternalHttpRequestException $externalHttpRequestException): AccessTokenResponseTransfer
    {
        $accessTokenResponseTransfer = (new AccessTokenResponseTransfer())
            ->setIsSuccessful(false);

        if (!empty($externalHttpRequestException->getResponseBody())) {
            /** @var array<string, mixed> $responseData */
            $responseData = $this->utilEncodingService->decodeJson($externalHttpRequestException->getResponseBody(), true);

            $oauthResponseErrorTransfer = (new AccessTokenErrorTransfer())
                ->setError($responseData[static::RESPONSE_KEY_ERROR] ?? null)
                ->setErrorDescription($responseData[static::RESPONSE_KEY_ERROR_DESCRIPTION] ?? null);
        } else {
            $oauthResponseErrorTransfer = (new AccessTokenErrorTransfer())
                ->setError('Response is empty.');
        }

        return $accessTokenResponseTransfer->setAccessTokenError($oauthResponseErrorTransfer);
    }
}
