<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClassQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\DataSet\ProductToProductClassDataSetInterface;

class ProductToProductClassWriterStep implements DataImportStepInterface
{
    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClassQuery $productToProductClassQuery
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery $productClassQuery
     */
    public function __construct(
        protected SpyProductToProductClassQuery $productToProductClassQuery,
        protected SpyProductQuery $productQuery,
        protected SpyProductClassQuery $productClassQuery
    ) {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idProduct = $dataSet[ProductToProductClassDataSetInterface::ID_PRODUCT];
        $idProductClass = $dataSet[ProductToProductClassDataSetInterface::ID_PRODUCT_CLASS];

        $productToProductClassEntity = $this->productToProductClassQuery
            ->clear()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductClass($idProductClass)
            ->findOneOrCreate();

        $productToProductClassEntity->save();
    }
}
