<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\ContentBannerDataImport\ContentBannerDataImportConfig;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentBannerDataImport\Business\ContentBannerDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\ContentBannerDataImport\ContentBannerDataImportConfig getConfig()
 */
class ContentBannerDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->importBannerTerm($dataImporterConfigurationTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @return string
     */
    public function getImportType(): string
    {
        return ContentBannerDataImportConfig::IMPORT_TYPE_CONTENT_BANNER;
    }
}
