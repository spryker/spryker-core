<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step;

use Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\DataSet\ProductToProductClassDataSetInterface;

class ProductClassKeyToIdProductClassStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected array $idProductClassCache = [];

    public function __construct(
        protected SpyProductClassQuery $productClassQuery
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
        /** @var string $productClassKey */
        $productClassKey = $dataSet[ProductToProductClassDataSetInterface::PRODUCT_CLASS_KEY];

        if (!isset($this->idProductClassCache[$productClassKey])) {
            $productClassEntity = $this->productClassQuery
                ->clear()
                ->filterByKey($productClassKey)
                ->findOne();

            if (!$productClassEntity) {
                throw new EntityNotFoundException(sprintf('Product class with key "%s" not found.', $productClassKey));
            }

            $this->idProductClassCache[$productClassKey] = $productClassEntity->getIdProductClass();
        }

        $dataSet[ProductToProductClassDataSetInterface::ID_PRODUCT_CLASS] = $this->idProductClassCache[$productClassKey];
    }
}
