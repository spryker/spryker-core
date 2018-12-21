<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsPageDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsPageDataImport\Business\CmsPageDataImportBusinessFactory getFactory()
 */
class CmsPageDataImportFacade extends AbstractFacade implements CmsPageDataImportFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importCmsPage(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFactory()->createCmsPageImporter()->import($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importCmsPageStore(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFactory()->createCmsPageStoreImporter()->import($dataImporterConfigurationTransfer);
    }
}
