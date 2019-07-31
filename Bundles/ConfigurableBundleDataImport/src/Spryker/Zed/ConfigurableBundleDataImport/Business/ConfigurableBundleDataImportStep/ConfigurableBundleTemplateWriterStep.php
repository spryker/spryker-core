<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleDataImport\Business\ConfigurableBundleDataImportStep;

use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Spryker\Zed\ConfigurableBundleDataImport\Business\DataSet\ConfigurableBundleTemplateDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ConfigurableBundleTemplateWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $configurableBundleTemplateEntity = $this->createConfigurableBundleTemplateQuery()
            ->filterByKey($dataSet[ConfigurableBundleTemplateDataSetInterface::COLUMN_CONFIGURABLE_BUNDLE_TEMPLATE_KEY])
            ->findOneOrCreate();

        $configurableBundleTemplateEntity
            ->setUuid($dataSet[ConfigurableBundleTemplateDataSetInterface::COLUMN_CONFIGURABLE_BUNDLE_TEMPLATE_UUID] ?: null)
            ->setName($dataSet[ConfigurableBundleTemplateDataSetInterface::COLUMN_CONFIGURABLE_BUNDLE_TEMPLATE_NAME])
            ->save();
    }

    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    protected function createConfigurableBundleTemplateQuery(): SpyConfigurableBundleTemplateQuery
    {
        return SpyConfigurableBundleTemplateQuery::create();
    }
}
