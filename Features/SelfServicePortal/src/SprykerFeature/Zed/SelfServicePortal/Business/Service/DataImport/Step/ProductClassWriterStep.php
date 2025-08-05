<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step;

use Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\DataSet\ProductClassDataSetInterface;

class ProductClassWriterStep implements DataImportStepInterface
{
    public function __construct(
        protected SpyProductClassQuery $productClassQuery
    ) {
    }

    public function execute(DataSetInterface $dataSet): void
    {
        $key = $dataSet[ProductClassDataSetInterface::KEY];
        $name = $dataSet[ProductClassDataSetInterface::NAME];

        $productClassEntity = $this->productClassQuery
            ->clear()
            ->filterByKey($key)
            ->findOneOrCreate();

        $productClassEntity
            ->setName($name)
            ->save();

        $dataSet[ProductClassDataSetInterface::ID_PRODUCT_CLASS] = $productClassEntity->getIdProductClass();
    }
}
