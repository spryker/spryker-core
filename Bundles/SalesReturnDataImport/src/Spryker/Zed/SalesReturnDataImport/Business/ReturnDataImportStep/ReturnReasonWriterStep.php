<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SalesReturnDataImport\Business\ReturnDataImportStep;

use Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\SalesReturnDataImport\Business\DataSet\ReturnReasonDataSetInterface;

class ReturnReasonWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\SalesReturnSearch\SalesReturnSearchConfig::RETURN_REASON_PUBLISH_WRITE
     */
    protected const EVENT_RETURN_REASON_PUBLISH_WRITE = 'Return.reason.publish_write';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $salesReturnReasonEntity = $this->getSalesReturnReasonQuery()
            ->filterByKey($dataSet[ReturnReasonDataSetInterface::COLUMN_REASON_KEY])
            ->findOneOrCreate();

        $salesReturnReasonEntity
            ->setGlossaryKeyReason($dataSet[ReturnReasonDataSetInterface::COLUMN_GLOSSARY_KEY_REASON])
            ->save();

        $this->addPublishEvents(
            static::EVENT_RETURN_REASON_PUBLISH_WRITE,
            $salesReturnReasonEntity->getIdSalesReturnReason()
        );
    }

    /**
     * @module SalesReturn
     *
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery
     */
    protected function getSalesReturnReasonQuery(): SpySalesReturnReasonQuery
    {
        return SpySalesReturnReasonQuery::create();
    }
}
