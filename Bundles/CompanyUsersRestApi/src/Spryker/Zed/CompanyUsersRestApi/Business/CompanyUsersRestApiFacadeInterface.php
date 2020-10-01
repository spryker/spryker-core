<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Business;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

interface CompanyUsersRestApiFacadeInterface
{
    /**
     * Specification:
     * - Expands the CustomerIdentifierTransfer with company user's uuid if it is set up in CustomerTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function expandCustomerIdentifier(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerIdentifierTransfer;

    /**
     * Specification
     * - Retrieves company user collection.
     * - The resulting collection can be filtered by company ID, company user IDs, company business unit IDs,
     * company role IDs and/or active flag.
     * - The resulting collection is filtered by FilterTransfer when it is provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): CompanyUserCollectionTransfer;

    /**
     * Specification
     * - Expands `QuoteTransfer.customer.companyUserTransfer`.
     * - Expects `QuoteTransfer.customer.companyUserTransfer.idCompanyUser` to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteCustomerWithCompanyUser(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification
     * - Expands `QuoteTransfer.customer.companyUserTransfer`.
     * - Expects `RestCheckoutRequestAttributesTransfer.customer.idCompanyUser` to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapCompanyUserToQuoteTransfer(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer;
}
