<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCategoryDataImport\Business\Step;

use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantCategoryDataImport\Business\DataSet\MerchantCategoryDataSetInterface;

class CategoryKeyToIdCategoryStep
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
        $categoryKey = $dataSet[MerchantCategoryDataSetInterface::CATEGORY_KEY];

        if (!$categoryKey) {
            throw new InvalidDataException(sprintf('"%s" is required.', MerchantCategoryDataSetInterface::CATEGORY_KEY));
        }

        $dataSet[MerchantCategoryDataSetInterface::FK_CATEGORY] = $this->getIdCategory($categoryKey);
    }

    /**
     * @param string $categoryKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCategory(string $categoryKey): int
    {
        if (isset($this->idCategoryCache[$categoryKey])) {
            return $this->idCategoryCache[$categoryKey];
        }

        /** @var \Orm\Zed\Category\Persistence\SpyCategoryQuery $categoryQuery */
        $categoryQuery = SpyCategoryQuery::create()
            ->select(SpyCategoryTableMap::COL_ID_CATEGORY);
        /** @var int $idCategory */
        $idCategory = $categoryQuery->findOneByCategoryKey($categoryKey);

        if (!$idCategory) {
            throw new EntityNotFoundException(sprintf('Could not find Category by key "%s"', $categoryKey));
        }

        $this->idCategoryCache[$categoryKey] = $idCategory;

        return $this->idCategoryCache[$categoryKey];
    }
}
