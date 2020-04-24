<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business;

use Generated\Shared\Transfer\DataExportReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DataExport\Business\DataExportBusinessFactory getFactory()
 * @method \Spryker\Zed\DataExport\Persistence\DataExportRepositoryInterface getRepository()
 * @method \Spryker\Zed\DataExport\Persistence\DataExportEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\DataExport\DataExportConfig getConfig()
 */
class DataExportFacade extends AbstractFacade implements DataExportFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param [] $dataEntityExportConfigurations
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportBatch(array $exportConfigurations): DataExportReportTransfer {
        return $this
            ->getFactory()
            ->createDataExportHandler()
            ->exportBatch($exportConfigurations);
    }
}
