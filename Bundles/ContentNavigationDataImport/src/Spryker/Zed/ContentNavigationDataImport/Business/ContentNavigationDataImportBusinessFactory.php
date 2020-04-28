<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;

/**
 * @method \Spryker\Zed\ContentNavigationDataImport\ContentNavigationDataImportConfig getConfig()
 */
class ContentNavigationDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getContentNavigationDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getContentBannerDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
      //  $dataSetStepBroker->addStep($this->createCheckContentDataStep());
        $dataSetStepBroker->addStep($this->createAddLocalesStep());
       // $dataSetStepBroker->addStep($this->createPrepareLocalizedContentBannerTermStep());
       // $dataSetStepBroker->addStep($this->createCheckLocalizedContentBannerTermStep());
       // $dataSetStepBroker->addStep($this->createContentBannerWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }
}
