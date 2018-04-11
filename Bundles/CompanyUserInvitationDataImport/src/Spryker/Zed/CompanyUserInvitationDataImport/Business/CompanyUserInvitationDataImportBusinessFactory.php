<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitationDataImport\Business;

use Spryker\Zed\CompanyUserInvitationDataImport\Business\Model\CompanyUserInvitationStatusWriterStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUserInvitationDataImport\CompanyUserInvitationDataImportConfig getConfig()
 */
class CompanyUserInvitationDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanyUserInvitationStatusDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getCompanyUserInvitationStatusDataImportConfiguration()
        );
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new CompanyUserInvitationStatusWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }
}
