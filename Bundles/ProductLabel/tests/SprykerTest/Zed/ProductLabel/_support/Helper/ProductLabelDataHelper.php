<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
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
     * @param array $seedData
     *
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
     * @param int $idProductLabel
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function haveProductLabelToAbstractProductRelation($idProductLabel, $idProductAbstract)
    {
        $this
            ->getProductLabelFacade()
            ->addAbstractProductRelationsForLabel($idProductLabel, [$idProductAbstract]);
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected function getProductLabelFacade()
    {
        return $this->getLocator()->productLabel()->facade();
    }
}
