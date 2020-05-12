<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business;

use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DataExport\Business\DataExportBusinessFactory getFactory()
 * @method \Spryker\Zed\DataExport\DataExportConfig getConfig()
 */
class DataExportFacade extends AbstractFacade implements DataExportFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer[]
     */
    public function exportDataEntities(DataExportConfigurationsTransfer $dataExportConfigurationsTransfer): array
    {
        return $this->getFactory()
            ->createDataExportHandler()
            ->exportDataEntities($dataExportConfigurationsTransfer);
    }
}
