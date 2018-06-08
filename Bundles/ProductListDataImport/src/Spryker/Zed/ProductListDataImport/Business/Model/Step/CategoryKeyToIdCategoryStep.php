<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListDataImport\Business\Model\Step;

use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductListDataImport\Business\Model\DataSet\ProductListDataSetInterface;

class CategoryKeyToIdCategoryStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idCategoryCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $categoryKey = $dataSet[ProductListDataSetInterface::CATEGORY_KEY];
        if (!$categoryKey) {
            throw new InvalidDataException(sprintf('"%s" is required.', ProductListDataSetInterface::CATEGORY_KEY));
        }

        $dataSet[ProductListDataSetInterface::ID_CATEGORY] = $this->getIdCategoryByKey($categoryKey);
    }

    /**
     * @param string $categoryKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCategoryByKey(string $categoryKey): int
    {
        if (!isset($this->idCategoryCache[$categoryKey])) {
            $idCategory = SpyCategoryQuery::create()
                ->select(SpyCategoryTableMap::COL_ID_CATEGORY)
                ->findOneByCategoryKey($categoryKey);

            if (!$idCategory) {
                throw new EntityNotFoundException(sprintf('Could not find Category by key "%s"', $categoryKey));
            }
            $this->idCategoryCache[$categoryKey] = $idCategory;
        }

        return $this->idCategoryCache[$categoryKey];
    }
}
