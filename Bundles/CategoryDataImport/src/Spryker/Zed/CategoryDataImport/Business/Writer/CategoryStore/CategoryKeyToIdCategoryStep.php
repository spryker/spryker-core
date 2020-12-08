<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore;

use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore\DataSet\CategoryStoreDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CategoryKeyToIdCategoryStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected static $idCategoryCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $categoryKey = $dataSet[CategoryStoreDataSetInterface::COL_CATEGORY_KEY];

        if (!$categoryKey) {
            throw new DataKeyNotFoundInDataSetException('Category key is missing');
        }

        $dataSet[CategoryStoreDataSetInterface::COL_ID_CATEGORY] = $this->getCategoryId($categoryKey);
    }

    /**
     * @param string $categoryKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getCategoryId(string $categoryKey): int
    {
        if (isset(static::$idCategoryCache[$categoryKey])) {
            return static::$idCategoryCache[$categoryKey];
        }

        $categoryEntity = SpyCategoryQuery::create()
            ->filterByCategoryKey($categoryKey)
            ->findOne();

        if ($categoryEntity === null) {
            throw new EntityNotFoundException(sprintf('Category not found: %s', $categoryKey));
        }

        static::$idCategoryCache[$categoryKey] = $categoryEntity->getIdCategory();

        return static::$idCategoryCache[$categoryKey];
    }
}
