<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitSalesConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CompanyBusinessUnitSalesConnectorTester extends Actor
{
    use _generated\CompanyBusinessUnitSalesConnectorTesterActions;

    /**
     * @param string $companyBusinessUnitUuid
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteWithCompanyUser(string $companyBusinessUnitUuid): QuoteTransfer
    {
        $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())->setUuid($companyBusinessUnitUuid);
        $companyUserTransfer = (new CompanyUserTransfer())->setCompanyBusinessUnit($companyBusinessUnitTransfer);
        $customerTransfer = (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer);

        return (new QuoteTransfer())->setCustomer($customerTransfer);
    }
}
