<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationRequest\Business\Exception\MerchantRelationshipNotCreatedException;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantRelationshipFacadeInterface;

class MerchantRelationshipCreator implements MerchantRelationshipCreatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantRelationshipFacadeInterface
     */
    protected MerchantRelationRequestToMerchantRelationshipFacadeInterface $merchantRelationshipFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     */
    public function __construct(
        MerchantRelationRequestToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
    ) {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return void
     */
    public function createMerchantRelationships(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): void
    {
        if (!$merchantRelationRequestTransfer->getIsSplitEnabled()) {
            $this->createMerchantRelationship(
                $merchantRelationRequestTransfer,
                $merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits()->getArrayCopy(),
            );

            return;
        }

        foreach ($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits() as $assigneeCompanyBusinessUnit) {
            $this->createMerchantRelationship(
                $merchantRelationRequestTransfer,
                [$assigneeCompanyBusinessUnit],
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param list<\Generated\Shared\Transfer\CompanyBusinessUnitTransfer> $assigneeCompanyBusinessUnits
     *
     * @throws \Spryker\Zed\MerchantRelationRequest\Business\Exception\MerchantRelationshipNotCreatedException
     *
     * @return void
     */
    protected function createMerchantRelationship(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        array $assigneeCompanyBusinessUnits
    ): void {
        $merchantRelationshipRequestTransfer = $this->createMerchantRelationshipRequestTransfer(
            $merchantRelationRequestTransfer,
            $assigneeCompanyBusinessUnits,
        );

        $merchantRelationshipResponseTransfer = $this->merchantRelationshipFacade->createMerchantRelationship(
            new MerchantRelationshipTransfer(),
            $merchantRelationshipRequestTransfer,
        );

        if (
            $merchantRelationshipResponseTransfer instanceof MerchantRelationshipResponseTransfer
            && !$merchantRelationshipResponseTransfer->getIsSuccessful()
        ) {
            throw new MerchantRelationshipNotCreatedException();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param list<\Generated\Shared\Transfer\CompanyBusinessUnitTransfer> $assigneeCompanyBusinessUnits
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer
     */
    protected function createMerchantRelationshipRequestTransfer(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        array $assigneeCompanyBusinessUnits
    ): MerchantRelationshipRequestTransfer {
        $companyBusinessUnitCollectionTransfer = (new CompanyBusinessUnitCollectionTransfer())
            ->setCompanyBusinessUnits(new ArrayObject($assigneeCompanyBusinessUnits));

        $merchantRelationshipTransfer = (new MerchantRelationshipTransfer())
            ->setMerchant($merchantRelationRequestTransfer->getMerchantOrFail())
            ->setOwnerCompanyBusinessUnit($merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail())
            ->setMerchantRelationRequestUuid($merchantRelationRequestTransfer->getUuidOrFail())
            ->setAssigneeCompanyBusinessUnits($companyBusinessUnitCollectionTransfer);

        return (new MerchantRelationshipRequestTransfer())
            ->setMerchantRelationship($merchantRelationshipTransfer);
    }
}
