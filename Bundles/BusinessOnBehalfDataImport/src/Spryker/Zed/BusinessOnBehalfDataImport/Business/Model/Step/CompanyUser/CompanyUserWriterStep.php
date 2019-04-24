<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step\CompanyUser;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\DataSet\BusinessOnBehalfCompanyUserDataSetInterface;
use Spryker\Zed\CompanyUser\Dependency\CompanyUserEvents;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUserWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses SpyCompanyUserQuery
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companyUserEntity = SpyCompanyUserQuery::create()
            ->filterByFkCompany($dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_ID_COMPANY])
            ->filterByFkCompanyBusinessUnit($dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_ID_BUSINESS_UNIT])
            ->filterByFkCustomer($dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_ID_CUSTOMER])
            ->findOneOrCreate();

        if (isset($dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_DEFAULT])) {
            $companyUserEntity->setIsDefault($dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_DEFAULT]);
        }

        $companyUserEntity->save();

        $this->addPublishEvents(
            CompanyUserEvents::COMPANY_USER_PUBLISH,
            $companyUserEntity->getIdCompanyUser()
        );
    }
}
