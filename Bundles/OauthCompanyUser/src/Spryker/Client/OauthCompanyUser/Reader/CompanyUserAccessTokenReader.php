<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCompanyUser\Reader;

use Generated\Shared\Transfer\CompanyUserAccessTokenRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\OauthCompanyUser\Zed\OauthCompanyUserStubInterface;

class CompanyUserAccessTokenReader implements CompanyUserAccessTokenReaderInterface
{
    /**
     * @var \Spryker\Client\OauthCompanyUser\Zed\OauthCompanyUserStubInterface
     */
    protected $oauthCompanyUserStub;

    /**
     * @param \Spryker\Client\OauthCompanyUser\Zed\OauthCompanyUserStubInterface $oauthCompanyUserStub
     */
    public function __construct(OauthCompanyUserStubInterface $oauthCompanyUserStub)
    {
        $this->oauthCompanyUserStub = $oauthCompanyUserStub;
    }

    /**
     * @param string $accessToken
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerByAccessToken(string $accessToken): CustomerTransfer
    {
        $companyUserAccessTokenRequestTransfer = (new CompanyUserAccessTokenRequestTransfer())
            ->setAccessToken($accessToken);

        return $this->oauthCompanyUserStub->getCustomerByAccessToken($companyUserAccessTokenRequestTransfer);
    }
}
