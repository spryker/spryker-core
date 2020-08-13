<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabelStore;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabelStore\DataSet\ProductLabelStoreDataSetInterface;

class ProductLabelNameToIdProductLabelStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected static $idProductLabelCache = [];

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
        $productLabelName = $dataSet[ProductLabelStoreDataSetInterface::COL_NAME];

        if (!$productLabelName) {
            throw new DataKeyNotFoundInDataSetException('Product label name is missing');
        }

        if (!isset(static::$idProductLabelCache[$productLabelName])) {
            $productLabelEntity = SpyProductLabelQuery::create()
                ->filterByName($productLabelName)
                ->findOne();

            if ($productLabelEntity === null) {
                throw new EntityNotFoundException(sprintf('Product label not found: %s', $productLabelName));
            }

            static::$idProductLabelCache[$productLabelName] = $productLabelEntity->getIdProductLabel();
        }

        $dataSet[ProductLabelStoreDataSetInterface::COL_ID_PRODUCT_LABEL] = static::$idProductLabelCache[$productLabelName];
    }
}
