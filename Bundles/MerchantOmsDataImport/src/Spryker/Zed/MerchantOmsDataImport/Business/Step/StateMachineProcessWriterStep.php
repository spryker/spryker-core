<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantOmsDataImport\Business\Step;

use Orm\Zed\StateMachine\Persistence\Base\SpyStateMachineProcessQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantOmsDataImport\Business\DataSet\MerchantOmsProcessDataSetInterface;

class StateMachineProcessWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Zed\MerchantOms\MerchantOmsConfig::MERCHANT_OMS_STATE_MACHINE_NAME
     */
    protected const MERCHANT_OMS_STATE_MACHINE_NAME = 'Merchant';
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

        $stateMachineProcessEntity = $this->createStateMachineProcessPropelQuery()
            ->filterByStateMachineName(static::MERCHANT_OMS_STATE_MACHINE_NAME)
            ->filterByName($merchantOmsProcessName)
            ->findOneOrCreate();

        if ($stateMachineProcessEntity->isNew() || $stateMachineProcessEntity->isModified()) {
            $stateMachineProcessEntity->save();
        }

        $dataSet[MerchantOmsProcessDataSetInterface::FK_STATE_MACHINE_PROCESS] = $stateMachineProcessEntity->getIdStateMachineProcess();
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\Base\SpyStateMachineProcessQuery
     */
    protected function createStateMachineProcessPropelQuery(): SpyStateMachineProcessQuery
    {
        return SpyStateMachineProcessQuery::create();
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
        if (!$dataSet[MerchantOmsProcessDataSetInterface::MERCHANT_OMS_PROCESS_NAME]) {
            throw new InvalidDataException(sprintf('"%s" is required.', MerchantOmsProcessDataSetInterface::MERCHANT_OMS_PROCESS_NAME));
        }
    }
}
