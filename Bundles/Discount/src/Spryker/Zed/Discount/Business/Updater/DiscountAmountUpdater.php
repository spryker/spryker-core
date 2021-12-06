<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\DiscountAmountCriteriaTransfer;
use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Spryker\Zed\Discount\Business\Mapper\DiscountMapperInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface;
use Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class DiscountAmountUpdater implements DiscountAmountUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface
     */
    protected $discountEntityManager;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface
     */
    protected $discountRepository;

    /**
     * @var \Spryker\Zed\Discount\Business\Mapper\DiscountMapperInterface
     */
    protected $discountMapper;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface $discountEntityManager
     * @param \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface $discountRepository
     * @param \Spryker\Zed\Discount\Business\Mapper\DiscountMapperInterface $discountMapper
     */
    public function __construct(
        DiscountEntityManagerInterface $discountEntityManager,
        DiscountRepositoryInterface $discountRepository,
        DiscountMapperInterface $discountMapper
    ) {
        $this->discountEntityManager = $discountEntityManager;
        $this->discountRepository = $discountRepository;
        $this->discountMapper = $discountMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function updateDiscountAmounts(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountConfiguratorTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($discountConfiguratorTransfer) {
            return $this->executeUpdateDiscountAmountsTransaction($discountConfiguratorTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function executeUpdateDiscountAmountsTransaction(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountConfiguratorTransfer
    {
        $idDiscount = $discountConfiguratorTransfer->getDiscountGeneralOrFail()->getIdDiscountOrFail();
        $discountCalculatorTransfer = $discountConfiguratorTransfer->getDiscountCalculatorOrFail();
        $requestedMoneyValueCollection = $discountCalculatorTransfer->getMoneyValueCollection();

        if (
            $discountCalculatorTransfer->getCalculatorPluginOrFail() !== DiscountDependencyProvider::PLUGIN_CALCULATOR_FIXED
            || $requestedMoneyValueCollection->count() === 0
        ) {
            return $this->deleteDiscountAmountsForDiscount($discountCalculatorTransfer, $discountConfiguratorTransfer);
        }

        $this->deleteExistingRemovedDiscountAmount($discountConfiguratorTransfer, $requestedMoneyValueCollection);

        foreach ($discountCalculatorTransfer->getMoneyValueCollection() as $moneyValueTransfer) {
            $discountMoneyAmountTransfer = $this->discountMapper->mapMoneyValueTransferToDiscountMoneyAmountTransfer(
                $moneyValueTransfer,
                new DiscountMoneyAmountTransfer(),
            );
            if ($moneyValueTransfer->getIdEntity()) {
                $this->discountEntityManager->updateDiscountAmount($discountMoneyAmountTransfer);

                continue;
            }

            $discountMoneyAmountTransfer->setFkDiscount($idDiscount);
            $this->discountEntityManager->createDiscountAmount($discountMoneyAmountTransfer);
        }

        return $discountConfiguratorTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountCalculatorTransfer $discountCalculatorTransfer
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function deleteDiscountAmountsForDiscount(
        DiscountCalculatorTransfer $discountCalculatorTransfer,
        DiscountConfiguratorTransfer $discountConfiguratorTransfer
    ): DiscountConfiguratorTransfer {
        $discountAmountCriteriaTransfer = (new DiscountAmountCriteriaTransfer())
            ->setIdDiscount($discountConfiguratorTransfer->getDiscountGeneralOrFail()->getIdDiscountOrFail());

        $this->discountEntityManager->deleteDiscountAmounts($discountAmountCriteriaTransfer);
        $discountCalculatorTransfer->setMoneyValueCollection(new ArrayObject());

        return $discountConfiguratorTransfer->setDiscountCalculator($discountCalculatorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\MoneyValueTransfer> $requestedMoneyValueTransfers
     *
     * @return void
     */
    protected function deleteExistingRemovedDiscountAmount(
        DiscountConfiguratorTransfer $discountConfiguratorTransfer,
        ArrayObject $requestedMoneyValueTransfers
    ): void {
        $existingMoneyValueTransfers = $this->discountRepository->getDiscountAmountCollectionForDiscount(
            $discountConfiguratorTransfer->getDiscountGeneralOrFail()->getIdDiscountOrFail(),
        );

        $existingDiscountAmountIds = $this->extractDiscountAmountIdsFromMoneyValueTransfers($existingMoneyValueTransfers);
        $updatedDiscountAmountIds = $this->extractDiscountAmountIdsFromMoneyValueTransfers($requestedMoneyValueTransfers->getArrayCopy());

        $discountAmountIdsToRemove = array_diff($existingDiscountAmountIds, $updatedDiscountAmountIds);
        if ($discountAmountIdsToRemove === []) {
            return;
        }

        $discountAmountCriteriaTransfer = (new DiscountAmountCriteriaTransfer())->setDiscountAmountIds($discountAmountIdsToRemove);

        $this->discountEntityManager->deleteDiscountAmounts($discountAmountCriteriaTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\MoneyValueTransfer> $moneyValueTransfers
     *
     * @return array<int>
     */
    protected function extractDiscountAmountIdsFromMoneyValueTransfers(array $moneyValueTransfers): array
    {
        return array_filter(array_map(function (MoneyValueTransfer $moneyValueTransfer) {
            return $moneyValueTransfer->getIdEntity();
        }, $moneyValueTransfers));
    }
}
