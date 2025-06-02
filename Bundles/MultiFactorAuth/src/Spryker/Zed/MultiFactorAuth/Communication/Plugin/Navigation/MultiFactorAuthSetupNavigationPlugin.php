<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Plugin\Navigation;

use Generated\Shared\Transfer\LinkTransfer;
use Spryker\Shared\GuiExtension\Dependency\Plugin\NavigationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 */
class MultiFactorAuthSetupNavigationPlugin extends AbstractPlugin implements NavigationPluginInterface
{
    /**
     * @var string
     */
    protected const URL_MULTI_FACTOR_AUTH_USER_MANAGEMENT_SET_UP = '/multi-factor-auth/user-management/set-up';

    /**
     * @var string
     */
    protected const LABEL_SET_UP_MULTI_FACTOR_AUTHENTICATION = 'Set up Multi-Factor Authentication';

    /**
     * {@inheritDoc}
     * - This method is used to create a navigation item for the Multi-Factor Authentication setup.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\LinkTransfer|null
     */
    public function getNavigationItem(): ?LinkTransfer
    {
        if (count($this->getFactory()->getUserMultiFactorAuthPlugins()) === 0) {
            return null;
        }

        return (new LinkTransfer())
            ->setUrl(static::URL_MULTI_FACTOR_AUTH_USER_MANAGEMENT_SET_UP)
            ->setLabel(static::LABEL_SET_UP_MULTI_FACTOR_AUTHENTICATION)
            ->setAttributes([
                'class' => 'js-mfa-setup',
            ]);
    }
}
