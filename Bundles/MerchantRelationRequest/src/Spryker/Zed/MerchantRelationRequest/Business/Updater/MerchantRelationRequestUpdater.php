<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRelationRequest\Business\Exception\MerchantRelationRequestUpdateStrategyNotFoundException;
use Spryker\Zed\MerchantRelationRequest\Business\Filter\MerchantRelationRequestFilterInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\MerchantRelationRequestValidatorInterface;

class MerchantRelationRequestUpdater implements MerchantRelationRequestUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Validator\MerchantRelationRequestValidatorInterface
     */
    protected MerchantRelationRequestValidatorInterface $merchantRelationRequestValidator;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Filter\MerchantRelationRequestFilterInterface
     */
    protected MerchantRelationRequestFilterInterface $merchantRelationRequestFilter;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Updater\AssigneeCompanyBusinessUnitUpdaterInterface
     */
    protected AssigneeCompanyBusinessUnitUpdaterInterface $assigneeCompanyBusinessUnitUpdater;

    /**
     * @var list<\Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\MerchantRelationRequestUpdaterStrategyInterface>
     */
    protected array $merchantRelationRequestUpdaterStrategies;

    /**
     * @var list<\Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostUpdatePluginInterface>
     */
    protected array $merchantRelationRequestPostUpdatePlugins;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Validator\MerchantRelationRequestValidatorInterface $merchantRelationRequestValidator
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Filter\MerchantRelationRequestFilterInterface $merchantRelationRequestFilter
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Updater\AssigneeCompanyBusinessUnitUpdaterInterface $assigneeCompanyBusinessUnitUpdater
     * @param list<\Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\MerchantRelationRequestUpdaterStrategyInterface> $merchantRelationRequestUpdaterStrategies
     * @param list<\Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostUpdatePluginInterface> $merchantRelationRequestPostUpdatePlugins
     */
    public function __construct(
        MerchantRelationRequestValidatorInterface $merchantRelationRequestValidator,
        MerchantRelationRequestFilterInterface $merchantRelationRequestFilter,
        AssigneeCompanyBusinessUnitUpdaterInterface $assigneeCompanyBusinessUnitUpdater,
        array $merchantRelationRequestUpdaterStrategies,
        array $merchantRelationRequestPostUpdatePlugins
    ) {
        $this->merchantRelationRequestValidator = $merchantRelationRequestValidator;
        $this->merchantRelationRequestFilter = $merchantRelationRequestFilter;
        $this->assigneeCompanyBusinessUnitUpdater = $assigneeCompanyBusinessUnitUpdater;
        $this->merchantRelationRequestUpdaterStrategies = $merchantRelationRequestUpdaterStrategies;
        $this->merchantRelationRequestPostUpdatePlugins = $merchantRelationRequestPostUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function updateMerchantRelationRequestCollection(
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
        $this->executeMerchantRelationRequestPostUpdatePlugins($merchantRelationRequestCollectionResponseTransfer);

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
                return $this->executeUpdateMerchantRelationRequestCollectionTransaction($validMerchantRelationRequestTransfers);
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
    protected function executeUpdateMerchantRelationRequestCollectionTransaction(
        ArrayObject $merchantRelationRequestTransfers
    ): ArrayObject {
        $persistedMerchantRelationRequestTransfers = new ArrayObject();

        foreach ($merchantRelationRequestTransfers as $entityIdentifier => $merchantRelationRequestTransfer) {
            $merchantRelationRequestTransfer = $this->executeMerchantRelationRequestUpdaterStrategies($merchantRelationRequestTransfer);
            $persistedMerchantRelationRequestTransfers->offsetSet(
                $entityIdentifier,
                $this->assigneeCompanyBusinessUnitUpdater->updateAssigneeCompanyBusinessUnits($merchantRelationRequestTransfer),
            );
        }

        return $persistedMerchantRelationRequestTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer): void
    {
        $merchantRelationRequestCollectionRequestTransfer
            ->requireIsTransactional()
            ->requireMerchantRelationRequests();

        foreach ($merchantRelationRequestCollectionRequestTransfer->getMerchantRelationRequests() as $merchantRelationRequestTransfer) {
            $merchantRelationRequestTransfer
                ->requireUuid()
                ->requireStatus();

            foreach ($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
                $companyBusinessUnitTransfer->requireIdCompanyBusinessUnit();
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @throws \Spryker\Zed\MerchantRelationRequest\Business\Exception\MerchantRelationRequestUpdateStrategyNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    protected function executeMerchantRelationRequestUpdaterStrategies(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestTransfer {
        foreach ($this->merchantRelationRequestUpdaterStrategies as $merchantRelationRequestUpdaterStrategy) {
            if (!$merchantRelationRequestUpdaterStrategy->isApplicable($merchantRelationRequestTransfer)) {
                continue;
            }

            return $merchantRelationRequestUpdaterStrategy->execute($merchantRelationRequestTransfer);
        }

        throw new MerchantRelationRequestUpdateStrategyNotFoundException();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return void
     */
    protected function executeMerchantRelationRequestPostUpdatePlugins(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): void {
        foreach ($this->merchantRelationRequestPostUpdatePlugins as $merchantRelationRequestPostUpdatePlugin) {
            $merchantRelationRequestPostUpdatePlugin->postUpdate($merchantRelationRequestCollectionResponseTransfer);
        }
    }
}
