<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step;

use Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractTypeQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\DataSet\ProductAbstractToProductAbstractTypeDataSetInterface;

class ProductAbstractTypeKeyToIdProductAbstractTypeStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected array $idProductAbstractTypeCache = [];

    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractTypeQuery $productAbstractTypeQuery
     */
    public function __construct(
        protected SpyProductAbstractTypeQuery $productAbstractTypeQuery
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
        /** @var string $productAbstractTypeKey */
        $productAbstractTypeKey = $dataSet[ProductAbstractToProductAbstractTypeDataSetInterface::PRODUCT_ABSTRACT_TYPE_KEY];

        if (!isset($this->idProductAbstractTypeCache[$productAbstractTypeKey])) {
            $productAbstractTypeEntity = $this->productAbstractTypeQuery
                ->clear()
                ->filterByKey($productAbstractTypeKey)
                ->findOne();

            if (!$productAbstractTypeEntity) {
                throw new EntityNotFoundException(sprintf('Product abstract type with key "%s" not found.', $productAbstractTypeKey));
            }

            $this->idProductAbstractTypeCache[$productAbstractTypeKey] = $productAbstractTypeEntity->getIdProductAbstractType();
        }

        $dataSet[ProductAbstractToProductAbstractTypeDataSetInterface::ID_PRODUCT_ABSTRACT_TYPE] = $this->idProductAbstractTypeCache[$productAbstractTypeKey];
    }
}
