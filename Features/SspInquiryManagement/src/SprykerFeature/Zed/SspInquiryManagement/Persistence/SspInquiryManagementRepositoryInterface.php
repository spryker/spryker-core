<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Persistence;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;

interface SspInquiryManagementRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer;

    /**
     * @param array<int> $stateIds
     *
     * @return array<\Generated\Shared\Transfer\StateMachineItemTransfer>
     */
    public function getStateMachineItemsByStateIds(array $stateIds): array;

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryFileCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryOrderCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer;

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerByIdCompanyUser(int $idCompanyUser): CustomerTransfer;
}
