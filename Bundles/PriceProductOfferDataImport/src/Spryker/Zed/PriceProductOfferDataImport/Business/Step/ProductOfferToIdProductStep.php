<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;

class ProductOfferToIdProductStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idProductCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (empty($dataSet[PriceProductOfferDataSetInterface::CONCRETE_SKU])) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                '"%s" must be in the data set. Given: "%s"',
                PriceProductOfferDataSetInterface::CONCRETE_SKU,
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }
        $dataSet[PriceProductOfferDataSetInterface::ID_PRODUCT_CONCRETE] = $this->resolveIdProductByConcreteSku($dataSet[PriceProductOfferDataSetInterface::CONCRETE_SKU]);
    }

    /**
     * @param string $sku
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function resolveIdProductByConcreteSku(string $sku): int
    {
        if (isset($this->idProductCache[$sku])) {
            return $this->idProductCache[$sku];
        }

        $productQuery = SpyProductQuery::create();
        $productQuery->select(SpyProductTableMap::COL_ID_PRODUCT);

        /** @var int $idProduct */
        $idProduct = $productQuery->findOneBySku($sku);

        if (!$idProduct) {
            throw new EntityNotFoundException(sprintf('Concrete product by sku "%s" not found.', $sku));
        }

        $this->idProductCache[$sku] = $idProduct;

        return $this->idProductCache[$sku];
    }
}
