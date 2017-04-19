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
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProduct($override = [])
    {
        $productFacade = $this->getProductFacade();
        $abstractProductId = $productFacade->createProductAbstract((new ProductAbstractBuilder())->build());

        $product = (new ProductConcreteBuilder(['fkProductAbstract' => $abstractProductId]))
            ->seed($override)
            ->build();

        $productFacade->createProductConcrete($product);
        $this->debug("Inserted AbstractProduct: $abstractProductId, Concrete Product: " . $product->getIdProductConcrete());

        $cleanupModule = $this->getDataCleanupHelper();
        $cleanupModule->_addCleanup(function () use ($product, $abstractProductId) {
            $this->debug("Deleting AbstractProduct: $abstractProductId, Concrete Product: " . $product->getIdProductConcrete());
            $this->getProductQuery()->queryProduct()->findByIdProduct($product->getIdProductConcrete())->delete();
            $this->getProductQuery()->queryProductAbstract()->findByIdProductAbstract($abstractProductId)->delete();
        });

        return $product;
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

}
