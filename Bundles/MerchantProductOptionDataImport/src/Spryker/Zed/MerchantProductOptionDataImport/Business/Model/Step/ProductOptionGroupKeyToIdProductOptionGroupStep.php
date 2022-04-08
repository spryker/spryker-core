<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOptionDataImport\Business\Model\Step;

use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOptionDataImport\Business\Model\DataSet\MerchantProductOptionDataSetInterface;

class ProductOptionGroupKeyToIdProductOptionGroupStep implements DataImportStepInterface
{
    /**
     * @var array<int>
     */
    protected $idProductOptionGroupCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<mixed> $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productOptionGroupKey = $dataSet[MerchantProductOptionDataSetInterface::PRODUCT_OPTION_GROUP_KEY];

        if (!$productOptionGroupKey) {
            throw new InvalidDataException('"' . MerchantProductOptionDataSetInterface::PRODUCT_OPTION_GROUP_KEY . '" is required.');
        }

        $dataSet[MerchantProductOptionDataSetInterface::ID_PRODUCT_OPTION_GROUP] = $this->getIdProductOptionGroup($productOptionGroupKey);
    }

    /**
     * @param string $productOptionGroupKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdProductOptionGroup(string $productOptionGroupKey): int
    {
        if (!isset($this->idProductOptionGroupCache[$productOptionGroupKey])) {
            $productOptionGroupQuery = SpyProductOptionGroupQuery::create()
                ->select(SpyProductOptionGroupTableMap::COL_ID_PRODUCT_OPTION_GROUP);
            /** @var int $idProductOptionGroup */
            $idProductOptionGroup = $productOptionGroupQuery->findOneByKey($productOptionGroupKey);

            if (!$idProductOptionGroup) {
                throw new EntityNotFoundException(sprintf('Could not find id Product Option Group by key "%s"', $productOptionGroupKey));
            }

            $this->idProductOptionGroupCache[$productOptionGroupKey] = $idProductOptionGroup;
        }

        return $this->idProductOptionGroupCache[$productOptionGroupKey];
    }
}
