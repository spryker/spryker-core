<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelationStore;

use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelationStore\DataSet\ProductRelationStoreDataSetInterface;

class ProductRelationKeyToIdProductRelationStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected static $idProductRelationCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productRelationKey = $dataSet[ProductRelationStoreDataSetInterface::COL_PRODUCT_RELATION_KEY];

        if (!$productRelationKey) {
            throw new DataKeyNotFoundInDataSetException('Product relation key is missing');
        }

        if (!isset(static::$idProductRelationCache[$productRelationKey])) {
            $productRelationEntity = SpyProductRelationQuery::create()
                ->filterByProductRelationKey($productRelationKey)
                ->findOne();

            if ($productRelationEntity === null) {
                throw new EntityNotFoundException(sprintf('Product relation not found: %s', $productRelationKey));
            }

            static::$idProductRelationCache[$productRelationKey] = $productRelationEntity->getIdProductRelation();
        }

        $dataSet[ProductRelationStoreDataSetInterface::COL_ID_PRODUCT_RELATION] = static::$idProductRelationCache[$productRelationKey];
    }
}
