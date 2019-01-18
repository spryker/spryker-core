<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface UserLocaleFacadeInterface
{
    /**
     * Specification:
     * - Returns User's Locale.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null
     */
    public function findUserLocale(UserTransfer $userTransfer): ?LocaleTransfer;

    /**
     * Specification:
     * - Returns default User's Locale.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getDefaultUserLocale(): LocaleTransfer;

    /**
     * Specification:
     * - Expands UserTransfer with Locale Id and Locale Name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function expandUserTransferWithLocale(UserTransfer $userTransfer): UserTransfer;
}
