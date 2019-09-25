<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteApproval;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class QuoteApprovalBusinessTester extends Actor
{
    use _generated\QuoteApprovalBusinessTesterActions;

    /**
     * @param string[] $statuses
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\QuoteApprovalTransfer[]
     */
    public function createQuoteApprovalTransfers(array $statuses): ArrayObject
    {
        $quoteApprovalTransfers = [];
        foreach ($statuses as $status) {
            $quoteApprovalTransfers[] = (new QuoteApprovalTransfer())->setStatus($status);
        }

        return new ArrayObject($quoteApprovalTransfers);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(): QuoteTransfer
    {
        $customerTransfer = $this->haveCustomer();
        $companyUserTransfer = $this->haveCompanyUser([
            CompanyUserTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $this->haveCompany()->getIdCompany(),
        ]);
        $customerTransfer->setCompanyUserTransfer($companyUserTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setStore(
                (new StoreTransfer())->setName('DE')
            )
            ->setCurrency(
                (new CurrencyTransfer())->setCode('EUR')
            );

        return $quoteTransfer;
    }
}
