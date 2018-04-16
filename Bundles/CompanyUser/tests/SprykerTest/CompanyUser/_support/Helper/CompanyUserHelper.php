<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUser\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyUserBuilder;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyUserHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function haveCompanyUser(array $seedData = []): CompanyUserTransfer
    {
        $companyUserTransfer = (new CompanyUserBuilder($seedData))->withCustomer()->build();
        $companyUserTransfer->setIdCompanyUser(null);

        $companyUserTransfer = $this->getCompanyUserFacade()->create($companyUserTransfer)->getCompanyUser();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($companyUserTransfer) {
            $this->getCompanyUserFacade()->delete($companyUserTransfer);
        });

        return $companyUserTransfer;
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    protected function getCompanyUserFacade()
    {
        return $this->getLocator()->companyUser()->facade();
    }
}
