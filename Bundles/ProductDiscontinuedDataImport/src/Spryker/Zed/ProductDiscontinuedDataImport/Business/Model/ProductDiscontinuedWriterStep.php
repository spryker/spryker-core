<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedDataImport\Business\Model;

use DateTime;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductDiscontinued\Dependency\ProductDiscontinuedEvents;
use Spryker\Zed\ProductDiscontinued\ProductDiscontinuedConfig;
use Spryker\Zed\ProductDiscontinuedDataImport\Business\Model\DataSet\ProductDiscontinuedDataSet;

class ProductDiscontinuedWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $productDiscontinuedEntity = SpyProductDiscontinuedQuery::create()
            ->filterByFkProduct($dataSet[ProductDiscontinuedDataSet::ID_PRODUCT])
            ->findOneOrCreate();

        $productDiscontinuedEntity->setActiveUntil($this->getActiveUntilDate());

        $productDiscontinuedEntity->save();

        $this->addPublishEvents(
            ProductDiscontinuedEvents::PRODUCT_DISCONTINUED_PUBLISH,
            $productDiscontinuedEntity->getIdProductDiscontinued()
        );
    }

    /**
     * @return string
     */
    protected function getActiveUntilDate(): string
    {
        return (new DateTime())
            ->modify(sprintf('+%s Days', ProductDiscontinuedConfig::DEFAULT_DAYS_AMOUNT_BEFORE_PRODUCT_DEACTIVATE))
            ->format('Y-m-d');
    }
}
