<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSet\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductSetBuilder;
use Generated\Shared\Transfer\ProductSetTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductSetDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $productSetOverwrite
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function haveProductSet(array $productSetOverwrite = [])
    {
        $productSetTransfer = (new ProductSetBuilder($productSetOverwrite))->build();
        if (isset($productSetOverwrite[ProductSetTransfer::LOCALIZED_DATA])) {
            $productSetTransfer->setLocalizedData($productSetOverwrite[ProductSetTransfer::LOCALIZED_DATA]);
        }

        return $this->getProductSetFacade()->createProductSet($productSetTransfer);
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\ProductSetFacadeInterface
     */
    protected function getProductSetFacade()
    {
        return $this->getLocator()->productSet()->facade();
    }
}
