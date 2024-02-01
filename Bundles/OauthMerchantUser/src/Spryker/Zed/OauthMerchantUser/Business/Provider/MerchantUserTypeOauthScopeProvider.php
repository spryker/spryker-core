<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthMerchantUser\Business\Provider;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Generated\Shared\Transfer\UserIdentifierTransfer;
use Spryker\Zed\OauthMerchantUser\Dependency\Facade\OauthMerchantUserToMerchantUserFacadeInterface;
use Spryker\Zed\OauthMerchantUser\OauthMerchantUserConfig;

class MerchantUserTypeOauthScopeProvider implements MerchantUserTypeOauthScopeProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthMerchantUser\Dependency\Facade\OauthMerchantUserToMerchantUserFacadeInterface
     */
    protected OauthMerchantUserToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @var \Spryker\Zed\OauthMerchantUser\OauthMerchantUserConfig
     */
    protected OauthMerchantUserConfig $oauthMerchantUserConfig;

    /**
     * @param \Spryker\Zed\OauthMerchantUser\Dependency\Facade\OauthMerchantUserToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\OauthMerchantUser\OauthMerchantUserConfig $oauthMerchantUserConfig
     */
    public function __construct(
        OauthMerchantUserToMerchantUserFacadeInterface $merchantUserFacade,
        OauthMerchantUserConfig $oauthMerchantUserConfig
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->oauthMerchantUserConfig = $oauthMerchantUserConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\UserIdentifierTransfer $userIdentifierTransfer
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(UserIdentifierTransfer $userIdentifierTransfer): array
    {
        $merchantUserCriteriaTransfer = (new MerchantUserCriteriaTransfer())->setIdUser($userIdentifierTransfer->getIdUserOrFail());
        $merchantUserTransfer = $this->merchantUserFacade->findMerchantUser($merchantUserCriteriaTransfer);

        if (!$merchantUserTransfer) {
            return [];
        }

        return [
            $this->createMerchantUserOauthScopeTransfer(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\OauthScopeTransfer
     */
    protected function createMerchantUserOauthScopeTransfer(): OauthScopeTransfer
    {
        return (new OauthScopeTransfer())
            ->setIdentifier($this->oauthMerchantUserConfig->getMerchantUserScope());
    }
}
