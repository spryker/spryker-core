<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Company\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyBuilder;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerTest\Shared\Customer\Helper\CustomerDataHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyHelper extends Module
{
    use LocatorHelperTrait;
    use CustomerDataHelperTrait;

    /**
     * @var string
     *
     * @see \Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap::COL_STATUS_APPROVED
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function haveCompany(array $seedData = []): CompanyTransfer
    {
        $companyTransfer = (new CompanyBuilder($seedData))->build();
        $companyTransfer->setIdCompany(null);

        return $this->getLocator()->company()->facade()->create($companyTransfer)->getCompanyTransfer();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function haveActiveCompanyWithUser(array $seedData = []): CompanyTransfer
    {
        $seedData[CompanyTransfer::IS_ACTIVE] = true;
        $seedData[CompanyTransfer::STATUS] = static::STATUS_APPROVED;

        $companyTransfer = (new CompanyBuilder($seedData))->build();
        $companyTransfer->setIdCompany(null);

        if (!$companyTransfer->getInitialUserTransfer()?->getFkCustomer()) {
            $customerTransfer = $this->getCustomerDataHelper()->haveConfirmedCustomerWithAddress($seedData);
            $companyUserTransfer = (new CompanyUserTransfer())
                ->setCustomer($customerTransfer)
                ->setFkCustomer($customerTransfer->getIdCustomer());
            $companyTransfer->setInitialUserTransfer($companyUserTransfer);
        }

        return $this->getLocator()->company()->facade()->create($companyTransfer)->getCompanyTransfer();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function haveActiveCompany(array $seedData = []): CompanyTransfer
    {
        $seedData[CompanyTransfer::IS_ACTIVE] = true;

        return $this->haveCompany($seedData);
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function haveInactiveCompany(array $seedData = []): CompanyTransfer
    {
        $seedData[CompanyTransfer::IS_ACTIVE] = false;

        return $this->haveCompany($seedData);
    }
}
