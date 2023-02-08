<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Communication\Plugin\User;

use Generated\Shared\Transfer\UserCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface;

/**
 * @method \Spryker\Zed\UserLocale\UserLocaleConfig getConfig()
 * @method \Spryker\Zed\UserLocale\Business\UserLocaleFacadeInterface getFacade()
 * @method \Spryker\Zed\UserLocale\Communication\UserLocaleCommunicationFactory getFactory()
 */
class LocaleUserExpanderPlugin extends AbstractPlugin implements UserExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves user locale from storage and expands a collection of UserTransfer with locale data.
     * - In case if `UserTransfer.fkLocale` and `UserTransfer.localeName` are undefined, expands `UserTransfer` with current locale data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function expand(UserCollectionTransfer $userCollectionTransfer): UserCollectionTransfer
    {
        return $this->getFacade()->expandUserCollectionWithLocale($userCollectionTransfer);
    }
}
