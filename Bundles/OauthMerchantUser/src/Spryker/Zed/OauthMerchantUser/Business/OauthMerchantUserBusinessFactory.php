<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthMerchantUser\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OauthMerchantUser\Business\Checker\MerchantUserTypeOauthScopeAuthorizationChecker;
use Spryker\Zed\OauthMerchantUser\Business\Checker\MerchantUserTypeOauthScopeAuthorizationCheckerInterface;
use Spryker\Zed\OauthMerchantUser\Business\Provider\MerchantUserTypeOauthScopeProvider;
use Spryker\Zed\OauthMerchantUser\Business\Provider\MerchantUserTypeOauthScopeProviderInterface;
use Spryker\Zed\OauthMerchantUser\Dependency\Facade\OauthMerchantUserToMerchantUserFacadeInterface;
use Spryker\Zed\OauthMerchantUser\OauthMerchantUserDependencyProvider;

/**
 * @method \Spryker\Zed\OauthMerchantUser\OauthMerchantUserConfig getConfig()
 */
class OauthMerchantUserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OauthMerchantUser\Business\Provider\MerchantUserTypeOauthScopeProviderInterface
     */
    public function createMerchantUserTypeOauthScopeProvider(): MerchantUserTypeOauthScopeProviderInterface
    {
        return new MerchantUserTypeOauthScopeProvider(
            $this->getMerchantUserFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\OauthMerchantUser\Business\Checker\MerchantUserTypeOauthScopeAuthorizationCheckerInterface
     */
    public function createMerchantUserTypeOauthScopeAuthorizationChecker(): MerchantUserTypeOauthScopeAuthorizationCheckerInterface
    {
        return new MerchantUserTypeOauthScopeAuthorizationChecker(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\OauthMerchantUser\Dependency\Facade\OauthMerchantUserToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): OauthMerchantUserToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(OauthMerchantUserDependencyProvider::FACADE_MERCHANT_USER);
    }
}
