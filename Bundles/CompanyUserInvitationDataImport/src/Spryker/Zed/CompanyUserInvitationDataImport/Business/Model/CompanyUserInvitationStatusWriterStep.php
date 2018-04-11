<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitationDataImport\Business\Model;

use Orm\Zed\CompanyUserInvitation\Persistence\Base\SpyCompanyUserInvitationStatusQuery;
use Spryker\Zed\CompanyUserInvitationDataImport\Business\Model\DataSet\CompanyUserInvitationStatusDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUserInvitationStatusWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $companyUserInvitationStatusEntity = SpyCompanyUserInvitationStatusQuery::create()
            ->filterByStatusKey($dataSet[CompanyUserInvitationStatusDataSet::STATUS_KEY])
            ->findOneOrCreate();

        $companyUserInvitationStatusEntity->fromArray($dataSet->getArrayCopy());

        $companyUserInvitationStatusEntity->save();
    }
}
