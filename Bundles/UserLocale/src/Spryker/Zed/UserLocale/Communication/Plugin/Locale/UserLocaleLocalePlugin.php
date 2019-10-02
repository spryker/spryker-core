<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Communication\Plugin\Locale;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\LocaleExtension\Dependency\Plugin\LocalePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\UserLocale\Communication\UserLocaleCommunicationFactory getFactory()
 * @method \Spryker\Zed\UserLocale\Business\UserLocaleFacadeInterface getFacade()
 * @method \Spryker\Zed\UserLocale\UserLocaleConfig getConfig()
 */
class UserLocaleLocalePlugin extends AbstractPlugin implements LocalePluginInterface
{
    /**
     * {@inheritDoc}
     * - Gets locale from UserTransfer.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleTransfer(ContainerInterface $container): LocaleTransfer
    {
        return $this->getFacade()->getCurrentUserLocale();
    }
}
