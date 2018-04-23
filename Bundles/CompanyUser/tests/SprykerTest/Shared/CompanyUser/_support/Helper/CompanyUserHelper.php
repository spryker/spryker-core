<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Shared\CompanyUser\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyUserBuilder;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyUserHelper extends Module
{
    use DataCleanupHelperTrait;
    use DependencyHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function haveCompanyUser(array $seed = [])
    {
        $companyUserTransfer = (new CompanyUserBuilder($seed))->build();
        $companyUserTransfer->setIdCompanyUser(null);

        $companyUserTransfer->requireCustomer();

        $companyUserTransfer = $companyUserResponseTransfer = $this->getFacade()
            ->create($companyUserTransfer)->getCompanyUser();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($companyUserTransfer) {
            $this->getFacade()->delete($companyUserTransfer);
        });

        return $companyUserTransfer;
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    protected function getFacade(): CompanyUserFacadeInterface
    {
        return $this->getLocator()->companyUser()->facade();
    }
}
