<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Creator;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface;
use Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface;

class AssigneeCompanyBusinessUnitCreator implements AssigneeCompanyBusinessUnitCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface
     */
    protected MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface
     */
    protected AssigneeCompanyBusinessUnitExtractorInterface $assigneeCompanyBusinessUnitExtractor;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface $assigneeCompanyBusinessUnitExtractor
     */
    public function __construct(
        MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager,
        AssigneeCompanyBusinessUnitExtractorInterface $assigneeCompanyBusinessUnitExtractor
    ) {
        $this->merchantRelationRequestEntityManager = $merchantRelationRequestEntityManager;
        $this->assigneeCompanyBusinessUnitExtractor = $assigneeCompanyBusinessUnitExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    public function createAssigneeCompanyBusinessUnits(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($merchantRelationRequestTransfer) {
            return $this->executeCreateAssigneeCompanyBusinessUnitsTransaction($merchantRelationRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    protected function executeCreateAssigneeCompanyBusinessUnitsTransaction(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestTransfer {
        $this->merchantRelationRequestEntityManager->createAssigneeCompanyBusinessUnits(
            $merchantRelationRequestTransfer->getIdMerchantRelationRequestOrFail(),
            $this->assigneeCompanyBusinessUnitExtractor->extractCompanyBusinessUnitIds($merchantRelationRequestTransfer),
        );

        return $merchantRelationRequestTransfer;
    }
}
