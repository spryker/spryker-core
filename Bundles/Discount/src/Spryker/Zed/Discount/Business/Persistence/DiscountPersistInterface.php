<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;

interface DiscountPersistInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return int
     */
    public function save(DiscountConfiguratorTransfer $discountConfiguratorTransfer);

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\PersistenceException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function update(DiscountConfiguratorTransfer $discountConfiguratorTransfer);

    /**
     * @param \Generated\Shared\Transfer\DiscountVoucherTransfer $discountVoucherTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\PersistenceException
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    public function saveVoucherCodes(DiscountVoucherTransfer $discountVoucherTransfer);

    /**
     * @param int $idDiscount
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\PersistenceException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function toggleDiscountVisibility($idDiscount, $isActive = false);
}
