<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Communication;

use Spryker\Service\DataExport\DataExportServiceInterface;
use Spryker\Zed\DataExport\DataExportDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\DataExport\DataExportConfig getConfig()
 * @method \Spryker\Zed\DataExport\Business\DataExportFacadeInterface getFacade()
 */
class DataExportCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Service\DataExport\DataExportServiceInterface
     */
    public function getDataExportService(): DataExportServiceInterface
    {
        return $this->getProvidedDependency(DataExportDependencyProvider::SERVICE_DATA_EXPORT);
    }
}
