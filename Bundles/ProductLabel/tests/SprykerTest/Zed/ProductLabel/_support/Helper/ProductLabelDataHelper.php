<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabel\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductLabelBuilder;
use Generated\Shared\DataBuilder\ProductLabelLocalizedAttributesBuilder;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
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
        $productLabelFacade = $this->getProductLabelFacade();
        /** @var \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer */
        $productLabelTransfer = (new ProductLabelBuilder($seedData + [
                ProductLabelTransfer::VALID_FROM => null,
                ProductLabelTransfer::VALID_TO => null,
            ]))->build();
        $productLabelTransfer->setIdProductLabel(null);
        $productLabelFacade->createLabel($productLabelTransfer);

        $productLabelLocalizedAttributesTransfer = (new ProductLabelLocalizedAttributesBuilder([
            ProductLabelLocalizedAttributesTransfer::FK_LOCALE => $this->getLocator()->locale()->facade()->getCurrentLocale()->getIdLocale(),
            ProductLabelLocalizedAttributesTransfer::FK_PRODUCT_LABEL => $productLabelTransfer->getIdProductLabel(),
        ]))->build();
        $productLabelTransfer->addLocalizedAttributes($productLabelLocalizedAttributesTransfer);

        $productLabelFacade->updateLabel($productLabelTransfer);

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
