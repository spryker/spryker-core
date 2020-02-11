<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\UserLocale\Business\UserLocaleBusinessFactory getFactory()
 */
class UserLocaleFacade extends AbstractFacade implements UserLocaleFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function expandUserTransferWithLocale(UserTransfer $userTransfer): UserTransfer
    {
        return $this->getFactory()
            ->createUserExpander()
            ->expandUserTransferWithLocale($userTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentUserLocale(): LocaleTransfer
    {
        return $this->getFactory()->createUserLocaleReader()->getCurrentUserLocaleTransfer();
    }
}
