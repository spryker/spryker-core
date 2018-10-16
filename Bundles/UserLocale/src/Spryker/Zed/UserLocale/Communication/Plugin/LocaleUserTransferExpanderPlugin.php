<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Communication\Plugin;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTransferExpanderPluginInterface;

/**
 * @method \Spryker\Zed\UserLocale\Communication\UserLocaleCommunicationFactory getFactory()
 * @method \Spryker\Zed\UserLocale\Business\UserLocaleFacade getFacade()()
 */
class LocaleUserTransferExpanderPlugin extends AbstractPlugin implements UserTransferExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands UserTransfer with locale code
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function expandTransfer(UserTransfer $userTransfer): UserTransfer
    {
        $userFkLocale = $userTransfer->getFkLocale();
        if ($userFkLocale === null) {
            return $userTransfer;
        }

        $localeTransfer = $this->getFactory()->getLocaleFacade()->getLocaleById($userFkLocale);
        $userTransfer->setLocaleCode($localeTransfer->getLocaleName());

        return $userTransfer;
    }
}
