<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDiscountConnector\Business\Checker;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CustomerDiscountConnector\CustomerDiscountConnectorConfig;
use Spryker\Zed\CustomerDiscountConnector\Dependency\Facade\CustomerDiscountConnectorToDiscountFacadeInterface;
use Spryker\Zed\CustomerDiscountConnector\Persistence\CustomerDiscountConnectorRepositoryInterface;

class CustomerOrderCountDecisionRuleChecker implements CustomerOrderCountDecisionRuleCheckerInterface
{
    /**
     * @var string
     */
    protected const METADATA_KEY_ID_DISCOUNT = 'id_discount';

    /**
     * @param \Spryker\Zed\CustomerDiscountConnector\Dependency\Facade\CustomerDiscountConnectorToDiscountFacadeInterface $discountFacade
     * @param \Spryker\Zed\CustomerDiscountConnector\CustomerDiscountConnectorConfig $customerDiscountConnectorConfig
     * @param \Spryker\Zed\CustomerDiscountConnector\Persistence\CustomerDiscountConnectorRepositoryInterface $customerDiscountConnectorRepository
     */
    public function __construct(
        protected CustomerDiscountConnectorToDiscountFacadeInterface $discountFacade,
        protected CustomerDiscountConnectorConfig $customerDiscountConnectorConfig,
        protected CustomerDiscountConnectorRepositoryInterface $customerDiscountConnectorRepository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isCustomerOrderCountSatisfiedBy(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): bool
    {
        $customer = $quoteTransfer->getCustomer();

        if ($customer === null || $customer->getIsGuest() === true || $customer->getIdCustomer() === null) {
            return false;
        }

        $idDiscount = $clauseTransfer->getMetadata()[static::METADATA_KEY_ID_DISCOUNT] ?? null;
        if (!is_numeric($idDiscount)) {
            return false;
        }

        $customerDiscountUsages = $this->customerDiscountConnectorRepository->countCustomerDiscountUsages(
            $customer->getIdCustomer(),
            (int)$idDiscount,
        );

        return $this->discountFacade->queryStringCompare($clauseTransfer, (string)$customerDiscountUsages);
    }
}
