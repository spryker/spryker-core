<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step;

use Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\DataSet\ProductAbstractTypeDataSetInterface;

class ProductAbstractTypeWriterStep implements DataImportStepInterface
{
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
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $key = $dataSet[ProductAbstractTypeDataSetInterface::KEY];
        $name = $dataSet[ProductAbstractTypeDataSetInterface::NAME];

        $productAbstractTypeEntity = $this->productAbstractTypeQuery
            ->clear()
            ->filterByKey($key)
            ->findOneOrCreate();

        $productAbstractTypeEntity
            ->setName($name)
            ->save();

        $dataSet[ProductAbstractTypeDataSetInterface::ID_PRODUCT_ABSTRACT_TYPE] = $productAbstractTypeEntity->getIdProductAbstractType();
    }
}
