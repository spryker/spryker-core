<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Discount;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountPostUpdatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 */
class DeleteDiscountVoucherPoolDiscountPostUpdatePlugin extends AbstractPlugin implements DiscountPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing when `DiscountConfiguratorTransfer.discountGeneral.discountType` is `voucher`.
     * - Does nothing when `DiscountConfiguratorTransfer.discountVoucher.fkDiscountVoucherPool` is not set.
     * - Deletes all vouchers from the voucher pool and the voucher pool itself by `DiscountConfiguratorTransfer.discountVoucher.fkDiscountVoucherPool`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function postUpdate(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountConfiguratorTransfer
    {
        return $this->getBusinessFactory()
            ->createDiscountVoucherPoolDeleter()
            ->deleteDiscountVoucherPoolByDiscountConfigurator($discountConfiguratorTransfer);
    }
}
