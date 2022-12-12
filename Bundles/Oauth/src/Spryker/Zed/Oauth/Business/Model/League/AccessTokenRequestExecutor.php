<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantBuilderInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeExecutorInterface;

class AccessTokenRequestExecutor implements AccessTokenRequestExecutorInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantBuilderInterface
     */
    protected $grantTypeBuilder;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeExecutorInterface
     */
    protected $grantTypeExecutor;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\RequestExecutorInterface
     */
    protected $requestExecutor;

    /**
     * @param \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantBuilderInterface $grantTypeBuilder
     * @param \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeExecutorInterface $grantTypeExecutor
     * @param \Spryker\Zed\Oauth\Business\Model\League\RequestExecutorInterface $requestExecutor
     */
    public function __construct(
        GrantBuilderInterface $grantTypeBuilder,
        GrantTypeExecutorInterface $grantTypeExecutor,
        RequestExecutorInterface $requestExecutor
    ) {
        $this->grantTypeBuilder = $grantTypeBuilder;
        $this->grantTypeExecutor = $grantTypeExecutor;
        $this->requestExecutor = $requestExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function executeByRequest(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        $oauthGrantTypeConfigurationTransfer = $this->requestExecutor->createOauthGrantTypeConfigurationTransfer($oauthRequestTransfer);

        if ($oauthGrantTypeConfigurationTransfer === null) {
            return $this->requestExecutor->createUnsupportedGrantTypeError($oauthRequestTransfer);
        }

        $grant = $this->grantTypeBuilder->buildGrant($oauthGrantTypeConfigurationTransfer);
        $oauthRequestTransfer = $this->requestExecutor->expandOauthRequestTransfer($oauthRequestTransfer);

        return $this->grantTypeExecutor->processAccessTokenRequest($oauthRequestTransfer, $grant);
    }
}
