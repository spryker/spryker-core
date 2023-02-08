<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface UserLocaleFacadeInterface
{
    /**
     * Specification:
     * - Retrieve user locale from storage and expands UserTransfer with Locale Id and Locale Name.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\UserLocale\Business\UserLocaleFacadeInterface::expandUserCollectionWithLocale()} instead.
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function expandUserTransferWithLocale(UserTransfer $userTransfer): UserTransfer;

    /**
     * Specification:
     * - Returns locale transfer for current user.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentUserLocale(): LocaleTransfer;

    /**
     * Specification:
     * - Retrieves user locale from storage and expands a collection of UserTransfer with locale data.
     * - In case if `UserTransfer.fkLocale` and `UserTransfer.localeName` are undefined, expands `UserTransfer` with current locale data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function expandUserCollectionWithLocale(UserCollectionTransfer $userCollectionTransfer): UserCollectionTransfer;
}
