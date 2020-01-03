<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Business\Creator;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Zed\OauthCompanyUser\Business\League\Grant\CompanyUserAccessTokenGrantType;
use Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToOauthFacadeInterface;

class CompanyUserAccessTokenCreator implements CompanyUserAccessTokenCreatorInterface
{
    /**
     * @var \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToOauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @var \Spryker\Zed\OauthCompanyUserExtension\Dependency\Plugin\CustomerOauthRequestMapperPluginInterface[]
     */
    protected $customerOauthRequestMapperPlugins;

    /**
     * @param \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToOauthFacadeInterface $oauthFacade
     * @param \Spryker\Zed\OauthCompanyUserExtension\Dependency\Plugin\CustomerOauthRequestMapperPluginInterface[] $customerOauthRequestMapperPlugins
     */
    public function __construct(
        OauthCompanyUserToOauthFacadeInterface $oauthFacade,
        array $customerOauthRequestMapperPlugins
    ) {
        $this->oauthFacade = $oauthFacade;
        $this->customerOauthRequestMapperPlugins = $customerOauthRequestMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function createAccessToken(CustomerTransfer $customerTransfer): OauthResponseTransfer
    {
        $customerTransfer
            ->requireCompanyUserTransfer()
            ->getCompanyUserTransfer()
                ->requireIdCompanyUser();

        $oauthRequestTransfer = (new OauthRequestTransfer())
            ->setIdCompanyUser((string)$customerTransfer->getCompanyUserTransfer()->getIdCompanyUser())
            ->setGrantType(CompanyUserAccessTokenGrantType::COMPANY_USER_ACCESS_TOKEN_GRANT_TYPE);

        $oauthRequestTransfer = $this->executeCustomerOauthRequestMapperPlugins($oauthRequestTransfer, $customerTransfer);

        return $this->oauthFacade->processAccessTokenRequest($oauthRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRequestTransfer
     */
    protected function executeCustomerOauthRequestMapperPlugins(
        OauthRequestTransfer $oauthRequestTransfer,
        CustomerTransfer $customerTransfer
    ): OauthRequestTransfer {
        foreach ($this->customerOauthRequestMapperPlugins as $customerOauthRequestMapperPlugin) {
            $oauthRequestTransfer = $customerOauthRequestMapperPlugin->map($oauthRequestTransfer, $customerTransfer);
        }

        return $oauthRequestTransfer;
    }
}
