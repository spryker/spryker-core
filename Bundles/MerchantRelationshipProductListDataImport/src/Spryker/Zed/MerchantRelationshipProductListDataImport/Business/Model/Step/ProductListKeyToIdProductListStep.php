<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListDataImport\Business\Model\Step;

use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantRelationshipProductListDataImport\Business\Model\DataSet\MerchantRelationshipProductListDataSetInterface;

class ProductListKeyToIdProductListStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idProductListCache = [];

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
        $productListKey = $dataSet[MerchantRelationshipProductListDataSetInterface::PRODUCT_LIST_KEY];
        if (!$productListKey) {
            throw new InvalidDataException(sprintf('"%s" is required.', MerchantRelationshipProductListDataSetInterface::PRODUCT_LIST_KEY));
        }

        if (!isset($this->idProductListCache[$productListKey])) {
            $idProductList = SpyProductListQuery::create()
                ->select(SpyProductListTableMap::COL_ID_PRODUCT_LIST)
                ->findOneByKey($productListKey);

            if (!$idProductList) {
                throw new EntityNotFoundException(sprintf('Could not find Product List by key "%s"', $productListKey));
            }
            $this->idProductListCache[$productListKey] = $idProductList;
        }
        $dataSet[MerchantRelationshipProductListDataSetInterface::ID_PRODUCT_LIST] = $this->idProductListCache[$productListKey];
    }
}
