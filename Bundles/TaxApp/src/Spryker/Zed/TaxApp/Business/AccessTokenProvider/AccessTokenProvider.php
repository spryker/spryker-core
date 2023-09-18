<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\AccessTokenProvider;

use Generated\Shared\Transfer\AccessTokenRequestOptionsTransfer;
use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Spryker\Zed\TaxApp\Business\Exception\AccessTokenNotFoundException;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToOauthClientFacadeInterface;
use Spryker\Zed\TaxApp\TaxAppConfig;

class AccessTokenProvider implements AccessTokenProviderInterface
{
    /**
     * @var \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToOauthClientFacadeInterface
     */
    protected TaxAppToOauthClientFacadeInterface $oauthClientFacade;

    /**
     * @var \Spryker\Zed\TaxApp\TaxAppConfig
     */
    protected TaxAppConfig $taxAppConfig;

    /**
     * @param \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToOauthClientFacadeInterface $oauthClientFacade
     * @param \Spryker\Zed\TaxApp\TaxAppConfig $taxAppConfig
     */
    public function __construct(
        TaxAppToOauthClientFacadeInterface $oauthClientFacade,
        TaxAppConfig $taxAppConfig
    ) {
        $this->oauthClientFacade = $oauthClientFacade;
        $this->taxAppConfig = $taxAppConfig;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        $accessTokenRequestOptionsTransfer = (new AccessTokenRequestOptionsTransfer())
            ->setAudience($this->taxAppConfig->getOauthOptionAudienceForTaxCalculation());

        $accessTokenRequestTransfer = (new AccessTokenRequestTransfer())
            ->setGrantType($this->taxAppConfig->getOauthGrantTypeForTaxCalculation())
            ->setProviderName($this->taxAppConfig->getOauthProviderNameForTaxCalculation())
            ->setAccessTokenRequestOptions($accessTokenRequestOptionsTransfer);

        return $this->getAuthorizationValue($accessTokenRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @throws \Spryker\Zed\TaxApp\Business\Exception\AccessTokenNotFoundException
     *
     * @return string
     */
    protected function getAuthorizationValue(AccessTokenRequestTransfer $accessTokenRequestTransfer): string
    {
        $accessTokenResponseTransfer = $this->oauthClientFacade->getAccessToken($accessTokenRequestTransfer);

        if (!$accessTokenResponseTransfer->getIsSuccessful()) {
            throw new AccessTokenNotFoundException(
                $accessTokenResponseTransfer->getAccessTokenErrorOrFail()->getErrorOrFail(),
            );
        }

        return sprintf('Bearer %s', $accessTokenResponseTransfer->getAccessToken());
    }
}
