<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business;

use Spryker\Service\DataExport\DataExportService;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\SalesDataExport\SalesDataExportConfig getConfig()
 * @method \Spryker\Zed\SalesDataExport\Persistence\SalesDataExportEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesDataExport\Persistence\SalesDataExportRepositoryInterface getRepository()
 */
class SalesDataExportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return DataExportService
     */
    public function getDataExportService(): DataExportService
    {
        return new DataExportService();
    }
}
