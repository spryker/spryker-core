<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Product\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductAbstractBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $productConcreteOverride
     * @param array $productAbstractOverride
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProduct(array $productConcreteOverride = [], array $productAbstractOverride = [])
    {
        $productAbstractTransfer = (new ProductAbstractBuilder($productAbstractOverride))->build();

        $productFacade = $this->getProductFacade();
        $abstractProductId = $productFacade->createProductAbstract($productAbstractTransfer);

        $productConcreteTransfer = (new ProductConcreteBuilder(['fkProductAbstract' => $abstractProductId]))
            ->seed($productConcreteOverride)
            ->build();

        $productConcreteTransfer->setAbstractSku($productAbstractTransfer->getSku());
        $productFacade->createProductConcrete($productConcreteTransfer);

        $this->debug(sprintf(
            'Inserted AbstractProduct: %d, Concrete Product: %d',
            $abstractProductId,
            $productConcreteTransfer->getIdProductConcrete()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productConcreteTransfer) {
            $this->cleanupProductConcrete($productConcreteTransfer->getIdProductConcrete());
            $this->cleanupProductAbstract($productConcreteTransfer->getFkProductAbstract());
        });

        return $productConcreteTransfer;
    }

    /**
     * @param array $productAbstractOverride
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function haveProductAbstract(array $productAbstractOverride = [])
    {
        $productAbstractTransfer = (new ProductAbstractBuilder($productAbstractOverride))->build();

        $productFacade = $this->getProductFacade();
        $abstractProductId = $productFacade->createProductAbstract($productAbstractTransfer);

        $this->debug(sprintf(
            'Inserted AbstractProduct: %d',
            $abstractProductId
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productAbstractTransfer) {
            $this->cleanupProductAbstract($productAbstractTransfer->getIdProductAbstract());
        });

        return $productAbstractTransfer;
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    private function getProductFacade()
    {
        return $this->getLocator()->product()->facade();
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainer
     */
    private function getProductQuery()
    {
        return $this->getLocator()->product()->queryContainer();
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    private function cleanupProductConcrete($idProductConcrete)
    {
        $this->debug(sprintf('Deleting Concrete Product: %d', $idProductConcrete));

        $this->getProductQuery()
            ->queryProduct()
            ->findByIdProduct($idProductConcrete)
            ->delete();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    private function cleanupProductAbstract($idProductAbstract)
    {
        $this->debug(sprintf('Deleting Abstract Product: %d', $idProductAbstract));

        $this->getProductQuery()
            ->queryProductAbstract()
            ->findByIdProductAbstract($idProductAbstract)
            ->delete();
    }
}
