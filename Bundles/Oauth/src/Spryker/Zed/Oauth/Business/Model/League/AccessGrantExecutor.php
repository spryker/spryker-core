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
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantConfigurationLoaderInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantExecutorInterface;

class AccessGrantExecutor implements AccessGrantExecutorInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantConfigurationLoaderInterface
     */
    protected $grantConfigurationLoader;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantBuilderInterface
     */
    protected $grantBuilder;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantExecutorInterface
     */
    protected $grantExecutor;

    /**
     * @param \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantConfigurationLoaderInterface $grantConfigurationLoader
     * @param \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantBuilderInterface $grantBuilder
     * @param \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantExecutorInterface $grantExecutor
     */
    public function __construct(
        GrantConfigurationLoaderInterface $grantConfigurationLoader,
        GrantBuilderInterface $grantBuilder,
        GrantExecutorInterface $grantExecutor
    ) {
        $this->grantConfigurationLoader = $grantConfigurationLoader;
        $this->grantBuilder = $grantBuilder;
        $this->grantExecutor = $grantExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function executeByRequest(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        $oauthGrantConfigurationTransfer = $this->grantConfigurationLoader
            ->loadGrantConfigurationByGrantType($oauthRequestTransfer);

        if (!$oauthGrantConfigurationTransfer) {
            return $this->createErrorResponseTransfer($oauthRequestTransfer);
        }

        $grant = $this->grantBuilder->buildGrant($oauthGrantConfigurationTransfer);

        return $this->grantExecutor->processAccessTokenRequest($oauthRequestTransfer, $grant);
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
