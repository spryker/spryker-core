<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use Generated\Shared\Transfer\OauthErrorTransfer;
use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeConfigurationLoaderInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\OauthGrantTypeConfigurationLoaderInterface;
use Spryker\Zed\Oauth\OauthConfig;

class RequestExecutor implements RequestExecutorInterface
{
    /**
     * @var string
     */
    protected const ERROR_TYPE_UNSUPPORTED_GRANT_TYPE = 'unsupported_grant_type';

    /**
     * @var string
     */
    protected const CLIENT_CONFIGURATION_KEY_IS_DEFAULT = 'isDefault';

    /**
     * @var string
     */
    protected const CLIENT_CONFIGURATION_KEY_IDENTIFIER = 'identifier';

    /**
     * @var string
     */
    protected const CLIENT_CONFIGURATION_KEY_SECRET = 'secret';

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeConfigurationLoaderInterface
     */
    protected $grantTypeConfigurationLoader;

    /**
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Grant\OauthGrantTypeConfigurationLoaderInterface
     */
    protected $oauthGrantTypeConfigurationLoader;

    /**
     * @param \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeConfigurationLoaderInterface $grantTypeConfigurationLoader
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     * @param \Spryker\Zed\Oauth\Business\Model\League\Grant\OauthGrantTypeConfigurationLoaderInterface $oauthGrantTypeConfigurationLoader
     */
    public function __construct(
        GrantTypeConfigurationLoaderInterface $grantTypeConfigurationLoader,
        OauthConfig $oauthConfig,
        OauthGrantTypeConfigurationLoaderInterface $oauthGrantTypeConfigurationLoader
    ) {
        $this->grantTypeConfigurationLoader = $grantTypeConfigurationLoader;
        $this->oauthConfig = $oauthConfig;
        $this->oauthGrantTypeConfigurationLoader = $oauthGrantTypeConfigurationLoader;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer|null
     */
    public function createOauthGrantTypeConfigurationTransfer(OauthRequestTransfer $oauthRequestTransfer): ?OauthGrantTypeConfigurationTransfer
    {
        $oauthGrantTypeConfigurationTransfer = new OauthGrantTypeConfigurationTransfer();
        $glueAuthenticationRequestContextTransfer = $oauthRequestTransfer->getGlueAuthenticationRequestContext();

        if ($glueAuthenticationRequestContextTransfer !== null) {
            $oauthGrantTypeConfigurationTransfer = $this->oauthGrantTypeConfigurationLoader
                ->loadGrantTypeConfiguration($oauthRequestTransfer, $glueAuthenticationRequestContextTransfer);
        }

        /*
         * For BC-reason only.
         */
        if ($glueAuthenticationRequestContextTransfer === null) {
            $oauthGrantTypeConfigurationTransfer = $this->loadGrantTypeConfigurationByGrantType($oauthRequestTransfer);
        }

        if (!$oauthGrantTypeConfigurationTransfer) {
            return null;
        }

        return $oauthGrantTypeConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function createUnsupportedGrantTypeError(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        $oauthResponseTransfer = new OauthResponseTransfer();
        $oauthErrorTransfer = new OauthErrorTransfer();
        $oauthErrorTransfer->setMessage(sprintf('Grant type "%s" not found', $oauthRequestTransfer->getGrantType()))
            ->setErrorType(static::ERROR_TYPE_UNSUPPORTED_GRANT_TYPE);
        $oauthResponseTransfer->setError($oauthErrorTransfer)
            ->setIsValid(false);

        return $oauthResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRequestTransfer
     */
    public function expandOauthRequestTransfer(OauthRequestTransfer $oauthRequestTransfer): OauthRequestTransfer
    {
        if ($oauthRequestTransfer->getClientId() && $oauthRequestTransfer->getClientSecret()) {
            return $oauthRequestTransfer;
        }

        foreach ($this->oauthConfig->getClientConfiguration() as $clientConfiguration) {
            if ($clientConfiguration[static::CLIENT_CONFIGURATION_KEY_IS_DEFAULT] === false) {
                continue;
            }

            return $oauthRequestTransfer
                ->setClientId($clientConfiguration[static::CLIENT_CONFIGURATION_KEY_IDENTIFIER])
                ->setClientSecret($clientConfiguration[static::CLIENT_CONFIGURATION_KEY_SECRET]);
        }

        return $this->expandOauthRequestTransferFallback($oauthRequestTransfer);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Oauth\Business\Model\League\Grant\OauthGrantTypeConfigurationLoaderInterface::loadGrantTypeConfiguration()} instead.
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer|null
     */
    protected function loadGrantTypeConfigurationByGrantType(
        OauthRequestTransfer $oauthRequestTransfer
    ): ?OauthGrantTypeConfigurationTransfer {
        return $this->grantTypeConfigurationLoader->loadGrantTypeConfigurationByGrantType($oauthRequestTransfer);
    }

    /**
     * @deprecated Exists for BC reasons. Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRequestTransfer
     */
    protected function expandOauthRequestTransferFallback(OauthRequestTransfer $oauthRequestTransfer): OauthRequestTransfer
    {
        return $oauthRequestTransfer
            ->setClientId($this->oauthConfig->getClientId())
            ->setClientSecret($this->oauthConfig->getClientSecret());
    }
}
