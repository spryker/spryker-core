<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\MerchantProduct\Step;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\MerchantProduct\DataSet\MerchantProductDataSetInterface;

class ProductAbstractSkuToIdProductAbstractStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $sku = $dataSet[MerchantProductDataSetInterface::PRODUCT_ABSTRACT_SKU];

        if (!$sku) {
            throw new InvalidDataException(sprintf('"%s" is required.', MerchantProductDataSetInterface::PRODUCT_ABSTRACT_SKU));
        }

        $dataSet[MerchantProductDataSetInterface::FK_PRODUCT_ABSTRACT] = $this->getIdAbstractProduct($sku);
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdAbstractProduct(string $sku): int
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery */
        $productAbstractQuery = SpyProductAbstractQuery::create()
            ->select(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
        /** @var int $idProductAbstract */
        $idProductAbstract = $productAbstractQuery->findOneBySku($sku);

        if (!$idProductAbstract) {
            throw new EntityNotFoundException(sprintf('Could not find Abstract Product by sku "%s"', $sku));
        }

        return $idProductAbstract;
    }
}
