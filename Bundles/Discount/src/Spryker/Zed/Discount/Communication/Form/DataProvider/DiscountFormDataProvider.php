<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

use DateTime;
use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Spryker\Zed\Discount\DiscountDependencyProvider;

class DiscountFormDataProvider
{

    /**
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer|null
     */
    public function getData($idDiscount)
    {
        if ($idDiscount) {
            return null;
        }

        $discountConfiguratorTransfer = new DiscountConfiguratorTransfer();

        $discountGeneralTransfer = $this->createDiscountGeneralTransferDefaults();
        $discountConfiguratorTransfer->setDiscountGeneral($discountGeneralTransfer);

        $calculatedDiscountTransfer = $this->createDiscountCalculatorTransfer();
        $discountConfiguratorTransfer->setDiscountCalculator($calculatedDiscountTransfer);

        return $discountConfiguratorTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountGeneralTransfer
     */
    protected function createDiscountGeneralTransferDefaults()
    {
        $discountGeneralTransfer = new DiscountGeneralTransfer();
        $discountGeneralTransfer->setIsExclusive(false);
        $discountGeneralTransfer->setValidFrom(new DateTime());
        $discountGeneralTransfer->setValidTo(new DateTime());
        return $discountGeneralTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountCalculatorTransfer
     */
    protected function createDiscountCalculatorTransfer()
    {
        $calculatedDiscountTransfer = new DiscountCalculatorTransfer();
        $calculatedDiscountTransfer->setCalculatorPlugin(DiscountDependencyProvider::PLUGIN_CALCULATOR_FIXED);

        return $calculatedDiscountTransfer;
    }

}
