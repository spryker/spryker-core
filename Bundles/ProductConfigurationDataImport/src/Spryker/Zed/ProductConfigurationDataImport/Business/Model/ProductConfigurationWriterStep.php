<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductConfigurationDataImport\Business\Model;

use Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery;
use Spryker\Shared\ProductConfigurationStorage\ProductConfigurationStorageConfig;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductConfigurationDataImport\Business\Model\DataSet\ProductConfigurationDataSet;

class ProductConfigurationWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productConfigurationEntity = SpyProductConfigurationQuery::create()
            ->filterByFkProduct($dataSet[ProductConfigurationDataSet::ID_PRODUCT_CONCRETE])
            ->findOneOrCreate();

        $productConfigurationEntity->setDefaultConfiguration($dataSet[ProductConfigurationDataSet::KEY_DEFAULT_CONFIGURATION])
            ->setDefaultDisplayData($dataSet[ProductConfigurationDataSet::KEY_DEFAULT_DISPLAY_DATA])
            ->setConfiguratorKey($dataSet[ProductConfigurationDataSet::KEY_CONFIGURATION_KEY])
            ->setIsComplete($dataSet[ProductConfigurationDataSet::KEY_IS_COMPLETE]);

        $this->addPublishEvents(
            ProductConfigurationStorageConfig::PRODUCT_CONFIGURATION_PUBLISH,
            $productConfigurationEntity->getIdProductConfiguration()
        );

        $productConfigurationEntity->save();
    }
}
