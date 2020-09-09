<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCategoryDataImport\Business\Step;

use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantCategoryDataImport\Business\DataSet\MerchantCategoryDataSetInterface;

class MerchantCategoryWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const REQUIRED_DATA_SET_KEYS = [
        MerchantCategoryDataSetInterface::FK_MERCHANT,
        MerchantCategoryDataSetInterface::FK_CATEGORY,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $merchantCategoryEntity = $this->createMerchantCategoryPropelQuery()
            ->filterByFkMerchant($dataSet[MerchantCategoryDataSetInterface::FK_MERCHANT])
            ->filterByFkCategory($dataSet[MerchantCategoryDataSetInterface::FK_CATEGORY])
            ->findOneOrCreate();

        $merchantCategoryEntity->fromArray($dataSet->getArrayCopy());
        $merchantCategoryEntity->save();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateDataSet(DataSetInterface $dataSet): void
    {
        foreach (static::REQUIRED_DATA_SET_KEYS as $requiredDataSetKey) {
            if (!$dataSet[$requiredDataSetKey]) {
                throw new InvalidDataException(sprintf('"%s" is required.', $requiredDataSetKey));
            }
        }
    }

    /**
     * @return \Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery
     */
    protected function createMerchantCategoryPropelQuery(): SpyMerchantCategoryQuery
    {
        return SpyMerchantCategoryQuery::create();
    }
}
