<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitationDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CompanyUserInvitationDataImport\CompanyUserInvitationDataImportConfig;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUserInvitationDataImport\Business\CompanyUserInvitationDataImportFacadeInterface getFacade()
 */
class CompanyUserInvitationStatusDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->import($dataImporterConfigurationTransfer);
    }

    /**
     * @return string
     */
    public function getImportType(): string
    {
        return CompanyUserInvitationDataImportConfig::IMPORT_TYPE_COMPANY_USER_INVITATION_STATUS;
    }
}
