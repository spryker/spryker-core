<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ConfigurableBundleDataImport\Business\ConfigurableBundleDataImportStep;

use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Spryker\Zed\ConfigurableBundle\Dependency\ConfigurableBundleEvents;
use Spryker\Zed\ConfigurableBundleDataImport\Business\DataSet\ConfigurableBundleTemplateSlotDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ConfigurableBundleTemplateSlotWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $configurableBundleTemplateSlotEntity = $this->createConfigurableBundleTemplateSlotQuery()
            ->filterByKey($dataSet[ConfigurableBundleTemplateSlotDataSetInterface::COLUMN_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_KEY])
            ->findOneOrCreate();

        $configurableBundleTemplateSlotEntity
            ->setName($dataSet[ConfigurableBundleTemplateSlotDataSetInterface::COLUMN_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NAME])
            ->setUuid($dataSet[ConfigurableBundleTemplateSlotDataSetInterface::COLUMN_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_UUID] ?: null)
            ->setFkConfigurableBundleTemplate($dataSet[ConfigurableBundleTemplateSlotDataSetInterface::ID_CONFIGURABLE_BUNDLE_TEMPLATE])
            ->setFkProductList($dataSet[ConfigurableBundleTemplateSlotDataSetInterface::ID_PRODUCT_LIST])
            ->save();

        $this->addPublishEvents(
            ConfigurableBundleEvents::CONFIGURABLE_BUNDLE_TEMPLATE_PUBLISH,
            $configurableBundleTemplateSlotEntity->getFkConfigurableBundleTemplate()
        );
    }

    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    protected function createConfigurableBundleTemplateSlotQuery(): SpyConfigurableBundleTemplateSlotQuery
    {
        return SpyConfigurableBundleTemplateSlotQuery::create();
    }
}
