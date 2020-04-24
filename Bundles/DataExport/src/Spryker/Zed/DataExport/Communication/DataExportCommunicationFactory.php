<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Communication;

use Spryker\Service\DataExport\DataExportService;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\DataExport\Persistence\DataExportQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\DataExport\DataExportConfig getConfig()
 */
class DataExportCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    public function getUtilReaderService() : \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
    {
        return new \Spryker\Service\UtilDataReader\UtilDataReaderService();
    }

    /**
     * @return DataExportService
     */
    public function getService(): DataExportService
    {
        return new DataExportService();
    }
}
