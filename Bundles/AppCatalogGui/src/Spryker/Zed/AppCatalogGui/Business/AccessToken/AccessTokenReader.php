<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppCatalogGui\Business\AccessToken;

use Generated\Shared\Transfer\AccessTokenErrorTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Spryker\Client\AppCatalogGui\AppCatalogGuiClientInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToTranslatorFacadeInterface;

class AccessTokenReader implements AccessTokenReaderInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Client\AppCatalogGui\AppCatalogGuiClientInterface
     */
    protected $appCatalogGuiClient;

    /**
     * @var \Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Client\AppCatalogGui\AppCatalogGuiClientInterface $appCatalogGuiClient
     * @param \Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        AppCatalogGuiClientInterface $appCatalogGuiClient,
        AppCatalogGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->appCatalogGuiClient = $appCatalogGuiClient;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function requestAccessToken(): AccessTokenResponseTransfer
    {
        $accessTokenResponseTransfer = $this->appCatalogGuiClient->requestAccessToken();

        if (!$accessTokenResponseTransfer->getIsSuccessful()) {
            $this->getLogger()->error(sprintf(
                'Reason: %s; Description: %s.',
                $accessTokenResponseTransfer->getAccessTokenErrorOrFail()->getError(),
                $accessTokenResponseTransfer->getAccessTokenErrorOrFail()->getErrorDescription(),
            ));

            $accessTokenResponseTransfer->setAccessTokenError(
                (new AccessTokenErrorTransfer())
                    ->setError($accessTokenResponseTransfer->getAccessTokenErrorOrFail()->getError())
                    ->setErrorDescription($this->translatorFacade->trans('Authentication failed.')),
            );
        }

        return $accessTokenResponseTransfer;
    }
}
