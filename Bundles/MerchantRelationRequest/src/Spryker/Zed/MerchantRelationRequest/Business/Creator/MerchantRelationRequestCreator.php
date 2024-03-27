<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRelationRequest\Business\Filter\MerchantRelationRequestFilterInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\MerchantRelationRequestValidatorInterface;
use Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface;

class MerchantRelationRequestCreator implements MerchantRelationRequestCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface
     */
    protected MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Validator\MerchantRelationRequestValidatorInterface
     */
    protected MerchantRelationRequestValidatorInterface $merchantRelationRequestValidator;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Filter\MerchantRelationRequestFilterInterface
     */
    protected MerchantRelationRequestFilterInterface $merchantRelationRequestFilter;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Creator\AssigneeCompanyBusinessUnitCreatorInterface
     */
    protected AssigneeCompanyBusinessUnitCreatorInterface $assigneeCompanyBusinessUnitCreator;

    /**
     * @var list<\Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostCreatePluginInterface>
     */
    protected array $merchantRelationRequestPostCreatePlugins;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Validator\MerchantRelationRequestValidatorInterface $merchantRelationRequestValidator
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Filter\MerchantRelationRequestFilterInterface $merchantRelationRequestFilter
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Creator\AssigneeCompanyBusinessUnitCreatorInterface $assigneeCompanyBusinessUnitCreator
     * @param list<\Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostCreatePluginInterface> $merchantRelationRequestPostCreatePlugins
     */
    public function __construct(
        MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager,
        MerchantRelationRequestValidatorInterface $merchantRelationRequestValidator,
        MerchantRelationRequestFilterInterface $merchantRelationRequestFilter,
        AssigneeCompanyBusinessUnitCreatorInterface $assigneeCompanyBusinessUnitCreator,
        array $merchantRelationRequestPostCreatePlugins
    ) {
        $this->merchantRelationRequestEntityManager = $merchantRelationRequestEntityManager;
        $this->merchantRelationRequestValidator = $merchantRelationRequestValidator;
        $this->merchantRelationRequestFilter = $merchantRelationRequestFilter;
        $this->assigneeCompanyBusinessUnitCreator = $assigneeCompanyBusinessUnitCreator;
        $this->merchantRelationRequestPostCreatePlugins = $merchantRelationRequestPostCreatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function createMerchantRelationRequestCollection(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        $merchantRelationRequestCollectionResponseTransfer = $this->validate($merchantRelationRequestCollectionRequestTransfer);

        if (
            $merchantRelationRequestCollectionRequestTransfer->getIsTransactional()
            && $merchantRelationRequestCollectionResponseTransfer->getErrors()->count()
        ) {
            return $merchantRelationRequestCollectionResponseTransfer;
        }

        $merchantRelationRequestCollectionResponseTransfer = $this->persist($merchantRelationRequestCollectionResponseTransfer);
        $this->executeMerchantRelationRequestPostCreatePlugins($merchantRelationRequestCollectionResponseTransfer);

        return $merchantRelationRequestCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    protected function persist(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        [$validMerchantRelationRequestTransfers, $invalidMerchantRelationRequestTransfers] = $this->merchantRelationRequestFilter
            ->filterMerchantRelationRequestsByValidity($merchantRelationRequestCollectionResponseTransfer);

        if ($validMerchantRelationRequestTransfers->count()) {
            $validMerchantRelationRequestTransfers = $this->getTransactionHandler()->handleTransaction(function () use ($validMerchantRelationRequestTransfers) {
                return $this->executeCreateMerchantRelationRequestCollectionTransaction($validMerchantRelationRequestTransfers);
            });
        }

        return $merchantRelationRequestCollectionResponseTransfer->setMerchantRelationRequests(
            $this->merchantRelationRequestFilter->mergeMerchantRelationRequests($validMerchantRelationRequestTransfers, $invalidMerchantRelationRequestTransfers),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    protected function validate(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        $this->assertRequiredFields($merchantRelationRequestCollectionRequestTransfer);
        $merchantRelationRequestCollectionResponseTransfer = (new MerchantRelationRequestCollectionResponseTransfer())
            ->setMerchantRelationRequests($merchantRelationRequestCollectionRequestTransfer->getMerchantRelationRequests());

        return $this->merchantRelationRequestValidator->validate($merchantRelationRequestCollectionResponseTransfer);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequestTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer>
     */
    protected function executeCreateMerchantRelationRequestCollectionTransaction(
        ArrayObject $merchantRelationRequestTransfers
    ): ArrayObject {
        $persistedMerchantRelationRequestTransfers = new ArrayObject();

        foreach ($merchantRelationRequestTransfers as $entityIdentifier => $merchantRelationRequestTransfer) {
            $merchantRelationRequestTransfer = $this->merchantRelationRequestEntityManager->createMerchantRelationRequest($merchantRelationRequestTransfer);
            $persistedMerchantRelationRequestTransfers->offsetSet(
                $entityIdentifier,
                $this->assigneeCompanyBusinessUnitCreator->createAssigneeCompanyBusinessUnits($merchantRelationRequestTransfer),
            );
        }

        return $persistedMerchantRelationRequestTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return void
     */
    protected function executeMerchantRelationRequestPostCreatePlugins(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): void {
        foreach ($this->merchantRelationRequestPostCreatePlugins as $merchantRelationRequestPostCreatePlugin) {
            $merchantRelationRequestPostCreatePlugin->postCreate($merchantRelationRequestCollectionResponseTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
    ): void {
        $merchantRelationRequestCollectionRequestTransfer
            ->requireIsTransactional()
            ->requireMerchantRelationRequests();

        foreach ($merchantRelationRequestCollectionRequestTransfer->getMerchantRelationRequests() as $merchantRelationRequestTransfer) {
            $merchantRelationRequestTransfer->requireStatus();
            $merchantRelationRequestTransfer->getMerchantOrFail()->requireIdMerchant();
            $merchantRelationRequestTransfer->getCompanyUserOrFail()->requireIdCompanyUser();
            $merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail()
                ->requireIdCompanyBusinessUnit()
                ->requireFkCompany();

            foreach ($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
                $companyBusinessUnitTransfer->requireIdCompanyBusinessUnit();
            }
        }
    }
}
