<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\DataSet\ProductAbstractToProductAbstractTypeDataSetInterface;

class ProductAbstractSkuToIdProductAbstractStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected array $idProductAbstractCache = [];

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery
     */
    public function __construct(
        protected SpyProductAbstractQuery $productAbstractQuery
    ) {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $abstractSku */
        $abstractSku = $dataSet[ProductAbstractToProductAbstractTypeDataSetInterface::ABSTRACT_SKU];

        if (!isset($this->idProductAbstractCache[$abstractSku])) {
            $productAbstractEntity = $this->productAbstractQuery
                ->clear()
                ->filterBySku($abstractSku)
                ->findOne();

            if (!$productAbstractEntity) {
                throw new EntityNotFoundException(sprintf('Product abstract with SKU "%s" not found.', $abstractSku));
            }

            $this->idProductAbstractCache[$abstractSku] = $productAbstractEntity->getIdProductAbstract();
        }

        $dataSet[ProductAbstractToProductAbstractTypeDataSetInterface::ID_PRODUCT_ABSTRACT] = $this->idProductAbstractCache[$abstractSku];
    }
}
