<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\CmsSlotBlockDataImport\src\Spryker\Zed\CmsSlotBlockDataImport\Business;

use Spryker\Zed\CmsPageDataImport\Business\CmsPage\CmsPageWriterStep;
use Spryker\Zed\CmsPageDataImport\Business\DataSet\CmsPageDataSet;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;

class CmsSlotBlockDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createCmsSlotBlockDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getCmsPageDataImporterConfiguration()
        );

        return $dataImporter;
    }
}
