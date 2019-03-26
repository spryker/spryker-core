<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabel\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery;
use Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyUnitAddressLabelDataHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return bool
     */
    public function haveCompanyUnitAddressLabelRelations(CompanyUnitAddressTransfer $companyUnitAddressTransfer): bool
    {
        $companyUnitAddressResponseTransfer = $this->getCompanyUnitAddressLabelFacade()->saveLabelToAddressRelations($companyUnitAddressTransfer);

        /** @var \SprykerTest\Shared\Testify\Helper\DataCleanupHelper $dataCleanupHelper */
        $dataCleanupHelper = $this->getDataCleanupHelper();
        $dataCleanupHelper->_addCleanup(function () use ($companyUnitAddressResponseTransfer) {
            foreach ($companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->getLabelCollection()->getLabels() as $companyUnitAddressLabel) {
                (new SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery())
                    ->filterByFkCompanyUnitAddress($companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->getIdCompanyUnitAddress())
                    ->filterByFkCompanyUnitAddressLabel($companyUnitAddressLabel->getIdCompanyUnitAddressLabel())
                    ->delete();
            }
        });

        return (bool)$companyUnitAddressResponseTransfer->getIsSuccessful();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface
     */
    protected function getCompanyUnitAddressLabelFacade(): CompanyUnitAddressLabelFacadeInterface
    {
        /** @var \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface $companyUnitAddressLabelFacade */
        $companyUnitAddressLabelFacade = $this->getLocator()->CompanyUnitAddressLabel()->facade();

        return $companyUnitAddressLabelFacade;
    }
}
