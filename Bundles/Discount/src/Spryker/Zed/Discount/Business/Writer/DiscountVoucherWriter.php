<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\VoucherTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;

class DiscountVoucherWriter extends AbstractWriter
{

    /**
     * @param \Generated\Shared\Transfer\VoucherTransfer $discountVoucherTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function create(VoucherTransfer $discountVoucherTransfer)
    {
        $discountVoucherEntity = new SpyDiscountVoucher();
        $discountVoucherEntity->fromArray($discountVoucherTransfer->toArray());
        $discountVoucherEntity->save();

        return $discountVoucherEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherTransfer $discountVoucherTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function update(VoucherTransfer $discountVoucherTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountVoucherEntity = $queryContainer
            ->queryDiscountVoucher()
            ->findPk($discountVoucherTransfer->getIdDiscountVoucher());
        $discountVoucherEntity->fromArray($discountVoucherTransfer->toArray());
        $discountVoucherEntity->save();

        return $discountVoucherEntity;
    }

}
