<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppCatalogGui\Business\AccessToken;

use Generated\Shared\Transfer\AccessTokenErrorTransfer;
use Generated\Shared\Transfer\AccessTokenRequestOptionsTransfer;
use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\AppCatalogGui\AppCatalogGuiConfig;
use Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToOauthClientFacadeInterface;
use Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToTranslatorFacadeInterface;

class AccessTokenReader implements AccessTokenReaderInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @var \Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToOauthClientFacadeInterface
     */
    protected $oauthClientFacade;

    /**
     * @var \Spryker\Zed\AppCatalogGui\AppCatalogGuiConfig
     */
    protected $applicationCatalogGuiConfig;

    /**
     * @param \Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToOauthClientFacadeInterface $oauthClientFacade
     * @param \Spryker\Zed\AppCatalogGui\AppCatalogGuiConfig $applicationCatalogGuiConfig
     */
    public function __construct(
        AppCatalogGuiToTranslatorFacadeInterface $translatorFacade,
        AppCatalogGuiToOauthClientFacadeInterface $oauthClientFacade,
        AppCatalogGuiConfig $applicationCatalogGuiConfig
    ) {
        $this->translatorFacade = $translatorFacade;
        $this->oauthClientFacade = $oauthClientFacade;
        $this->applicationCatalogGuiConfig = $applicationCatalogGuiConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function requestAccessToken(): AccessTokenResponseTransfer
    {
        $accessTokenRequestOptions = (new AccessTokenRequestOptionsTransfer())
            ->setAudience($this->applicationCatalogGuiConfig->getOauthAudience());

        $accessTokenRequestTransfer = (new AccessTokenRequestTransfer())
            ->setGrantType($this->applicationCatalogGuiConfig->getOauthGrantType())
            ->setProviderName($this->applicationCatalogGuiConfig->getOauthProviderName())
            ->setAccessTokenRequestOptions($accessTokenRequestOptions);

        $oauthClientResponseTransfer = $this->oauthClientFacade->getAccessToken($accessTokenRequestTransfer);

        if (!$oauthClientResponseTransfer->getIsSuccessful()) {
            $this->getLogger()->error(sprintf(
                'Reason: %s; Description: %s.',
                $oauthClientResponseTransfer->getAccessTokenErrorOrFail()->getError(),
                $oauthClientResponseTransfer->getAccessTokenErrorOrFail()->getErrorDescription(),
            ));

            $oauthClientResponseTransfer->setAccessTokenError(
                (new AccessTokenErrorTransfer())
                    ->setError($oauthClientResponseTransfer->getAccessTokenErrorOrFail()->getError())
                    ->setErrorDescription($this->translatorFacade->trans('Authentication failed.')),
            );
        }

        return $oauthClientResponseTransfer;
    }
}
