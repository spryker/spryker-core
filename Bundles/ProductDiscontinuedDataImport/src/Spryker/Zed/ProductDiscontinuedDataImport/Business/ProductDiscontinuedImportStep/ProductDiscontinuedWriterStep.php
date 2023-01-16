<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductDiscontinuedDataImport\Business\ProductDiscontinuedImportStep;

use DateTime;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedNoteQuery;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductDiscontinuedDataImport\Business\ProductDiscontinuedDataSet\ProductDiscontinuedDataSetInterface;
use Spryker\Zed\ProductDiscontinuedDataImport\ProductDiscontinuedDataImportConfig;

class ProductDiscontinuedWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses {@link \Spryker\Shared\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig::PRODUCT_DISCONTINUED_PUBLISH}.
     *
     * @var string
     */
    protected const PRODUCT_DISCONTINUED_PUBLISH = 'ProductDiscontinued.product_discontinued.publish';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $productDiscontinuedEntity = SpyProductDiscontinuedQuery::create()
            ->filterByFkProduct($dataSet[ProductDiscontinuedDataSetInterface::ID_PRODUCT])
            ->findOneOrCreate();

        $productDiscontinuedEntity->setActiveUntil($this->getActiveUntilDate());

        $productDiscontinuedEntity->save();

        $this->saveLocalizedNotes($productDiscontinuedEntity, $dataSet);

        $this->addPublishEvents(
            static::PRODUCT_DISCONTINUED_PUBLISH,
            $productDiscontinuedEntity->getIdProductDiscontinued(),
        );
    }

    /**
     * @return string
     */
    protected function getActiveUntilDate(): string
    {
        return (new DateTime())
            ->modify(sprintf('+%s Days', ProductDiscontinuedDataImportConfig::DEFAULT_DAYS_AMOUNT_BEFORE_PRODUCT_DEACTIVATE))
            ->format('Y-m-d');
    }

    /**
     * @param \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued $productDiscontinuedEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function saveLocalizedNotes(SpyProductDiscontinued $productDiscontinuedEntity, DataSetInterface $dataSet): void
    {
        if (empty($dataSet[ProductDiscontinuedDataSetInterface::KEY_LOCALIZED_NOTES])) {
            return;
        }
        foreach ($dataSet[ProductDiscontinuedDataSetInterface::KEY_LOCALIZED_NOTES] as $localeId => $localizedNote) {
            $productDiscontinuedNoteEntity = SpyProductDiscontinuedNoteQuery::create()
                ->filterByFkProductDiscontinued($productDiscontinuedEntity->getIdProductDiscontinued())
                ->filterByFkLocale($localeId)
                ->findOneOrCreate();

            $productDiscontinuedNoteEntity->setNote($localizedNote[ProductDiscontinuedDataSetInterface::KEY_NOTE]);

            $productDiscontinuedNoteEntity->save();
        }
    }
}
