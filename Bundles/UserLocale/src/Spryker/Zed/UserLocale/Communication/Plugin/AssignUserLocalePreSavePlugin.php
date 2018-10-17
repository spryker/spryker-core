<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Communication\Plugin;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserPreSavePluginInterface;

/**
 * @method \Spryker\Zed\UserLocale\UserLocaleConfig getConfig()
 * @method \Spryker\Zed\UserLocale\Communication\UserLocaleCommunicationFactory getFactory()
 * @method \Spryker\Zed\UserLocale\Business\UserLocaleFacadeInterface getFacade()
 */
class AssignUserLocalePreSavePlugin extends AbstractPlugin implements UserPreSavePluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function preSave(UserTransfer $userTransfer): UserTransfer
    {
        if (!$userTransfer->getFkLocale()) {
            $localeTransfer = $this->getFactory()->getLocaleFacade()->getLocale($this->getConfig()->getDefaultLocale());
            $userTransfer->setFkLocale($localeTransfer->getIdLocale());
        }

        return $userTransfer;
    }
}
