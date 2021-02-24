<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    protected const CONCRETE_SKU = MerchantProductOfferDataSetInterface::CONCRETE_SKU;

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
        $concreteProductSku = $dataSet[static::CONCRETE_SKU];

        if (!$concreteProductSku) {
            throw new InvalidDataException('"' . static::CONCRETE_SKU . '" is required.');
        }

        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
        $productQuery = SpyProductQuery::create();
        $productQuery->filterBySku($concreteProductSku);

        if (!$productQuery->exists()) {
            throw new EntityNotFoundException(sprintf('Could not find Product by sku "%s"', $concreteProductSku));
        }
    }
}
