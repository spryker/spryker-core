<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentBannerDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ContentBannerDataImport\Business\ContentBannerDataImportBusinessFactory getFactory()
 */
class ContentBannerDataImportFacade extends AbstractFacade implements ContentBannerDataImportFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importBannerTerm(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFactory()->getContentBannerDataImport()->import($dataImporterConfigurationTransfer);
    }
}
