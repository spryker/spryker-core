<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;

class ConcreteSkuValidationStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $concreteProductSku = $dataSet[MerchantProductOfferDataSetInterface::CONCRETE_SKU];

        if (!$concreteProductSku) {
            throw new InvalidDataException('"' . MerchantProductOfferDataSetInterface::CONCRETE_SKU . '" is required.');
        }

        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
        $productQuery = SpyProductQuery::create();
        $productQuery->filterBySku($concreteProductSku);

        if (!$productQuery->exists()) {
            throw new EntityNotFoundException(sprintf('Could not find Product by sku "%s"', $concreteProductSku));
        }
    }
}
