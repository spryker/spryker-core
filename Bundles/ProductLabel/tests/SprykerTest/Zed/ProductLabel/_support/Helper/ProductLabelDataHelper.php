<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabel\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductLabelBuilder;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductLabelDataHelper extends Module
{

    use LocatorHelperTrait;

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function haveProductLabel(array $seedData = [])
    {
        $productLabelTransfer = (new ProductLabelBuilder($seedData))->build();
        $productLabelTransfer->setIdProductLabel(null);
        $this->getProductLabelFacade()->createLabel($productLabelTransfer);

        return $productLabelTransfer;
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected function getProductLabelFacade()
    {
        return $this->getLocator()->productLabel()->facade();
    }

}
