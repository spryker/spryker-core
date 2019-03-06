<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use Generated\Shared\Transfer\OauthErrorTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantBuilderInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeConfigurationLoaderInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeExecutorInterface;

class AccessTokenRequestExecutor implements AccessTokenRequestExecutorInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeConfigurationLoaderInterface
     */
    protected $grantTypeConfigurationLoader;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantBuilderInterface
     */
    protected $grantTypeBuilder;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeExecutorInterface
     */
    protected $grantTypeExecutor;

    /**
     * @param \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeConfigurationLoaderInterface $grantTypeConfigurationLoader
     * @param \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantBuilderInterface $grantTypeBuilder
     * @param \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeExecutorInterface $grantTypeExecutor
     */
    public function __construct(
        GrantTypeConfigurationLoaderInterface $grantTypeConfigurationLoader,
        GrantBuilderInterface $grantTypeBuilder,
        GrantTypeExecutorInterface $grantTypeExecutor
    ) {
        $this->grantTypeConfigurationLoader = $grantTypeConfigurationLoader;
        $this->grantTypeBuilder = $grantTypeBuilder;
        $this->grantTypeExecutor = $grantTypeExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function executeByRequest(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        $oauthGrantTypeConfigurationTransfer = $this->grantTypeConfigurationLoader
            ->loadGrantTypeConfigurationByGrantType($oauthRequestTransfer);

        if (!$oauthGrantTypeConfigurationTransfer) {
            return $this->createErrorResponseTransfer($oauthRequestTransfer);
        }

        $grant = $this->grantTypeBuilder->buildGrant($oauthGrantTypeConfigurationTransfer);

        return $this->grantTypeExecutor->processAccessTokenRequest($oauthRequestTransfer, $grant);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected function createErrorResponseTransfer(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        $oauthResponseTransfer = new OauthResponseTransfer();
        $oauthErrorTransfer = new OauthErrorTransfer();
        $oauthErrorTransfer->setMessage(sprintf('Grant type "%s" not found', $oauthRequestTransfer->getGrantType()));
        $oauthResponseTransfer->setError($oauthErrorTransfer)
            ->setIsValid(false);

        return $oauthResponseTransfer;
    }
}
