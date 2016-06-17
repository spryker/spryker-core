<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;

class DiscountFormDataProvider
{

    /**
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountVoucherTransfer|null
     */
    public function getData($idDiscount)
    {
        $discountConfiguratorTransfer = null;
        if (!$idDiscount) {
            $discountConfiguratorTransfer = new DiscountConfiguratorTransfer();
            $discountGeneralTransfer = new DiscountGeneralTransfer();
            $discountGeneralTransfer->setIdDiscount($idDiscount);
            $discountGeneralTransfer->setIsExclusive(false);
            $discountGeneralTransfer->setValidFrom(new \DateTime());
            $discountGeneralTransfer->setValidTo(new \DateTime());

            $discountConfiguratorTransfer->setDiscountGeneral($discountGeneralTransfer);
        }

        return $discountConfiguratorTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

}
