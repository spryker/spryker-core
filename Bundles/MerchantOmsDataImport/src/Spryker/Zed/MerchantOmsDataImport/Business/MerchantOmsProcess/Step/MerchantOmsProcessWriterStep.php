<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantOmsDataImport\Business\MerchantOmsProcess\Step;

use Orm\Zed\MerchantOms\Persistence\Base\SpyMerchantOmsProcessQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantOmsDataImport\Business\MerchantOmsProcess\DataSet\MerchantOmsProcessDataSetInterface;

class MerchantOmsProcessWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const REQUIRED_DATA_SET_KEYS = [
        MerchantOmsProcessDataSetInterface::MERCHANT_OMS_PROCESS_NAME,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $merchantOmsProcessName = $dataSet[MerchantOmsProcessDataSetInterface::MERCHANT_OMS_PROCESS_NAME];

        $merchantOmsProcessEntity = $this->createMerchantOmsProcessPropelQuery()
            ->filterByProcessName($merchantOmsProcessName)
            ->findOneOrCreate();

        $merchantOmsProcessEntity->setProcessName($merchantOmsProcessName);

        if ($merchantOmsProcessEntity->isNew() || $merchantOmsProcessEntity->isModified()) {
            $merchantOmsProcessEntity->save();
        }

        $dataSet[MerchantOmsProcessDataSetInterface::FK_MERCHANT_OMS_PROCESS] = $merchantOmsProcessEntity->getIdMerchantOmsProcess();
    }

    /**
     * @return \Orm\Zed\MerchantOms\Persistence\Base\SpyMerchantOmsProcessQuery
     */
    protected function createMerchantOmsProcessPropelQuery(): SpyMerchantOmsProcessQuery
    {
        return SpyMerchantOmsProcessQuery::create();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function validateDataSet(DataSetInterface $dataSet): void
    {
        foreach (static::REQUIRED_DATA_SET_KEYS as $requiredDataSetKey) {
            $this->validateRequireDataSetByKey($dataSet, $requiredDataSetKey);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $requiredDataSetKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateRequireDataSetByKey(DataSetInterface $dataSet, string $requiredDataSetKey): void
    {
        if (!$dataSet[$requiredDataSetKey]) {
            throw new InvalidDataException(sprintf('"%s" is required.', $requiredDataSetKey));
        }
    }
}
