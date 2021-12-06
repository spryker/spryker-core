<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Creator;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Spryker\Zed\Discount\Business\Mapper\DiscountMapperInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class DiscountAmountCreator implements DiscountAmountCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface
     */
    protected $discountEntityManager;

    /**
     * @var \Spryker\Zed\Discount\Business\Mapper\DiscountMapperInterface
     */
    protected $discountMapper;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface $discountEntityManager
     * @param \Spryker\Zed\Discount\Business\Mapper\DiscountMapperInterface $discountMapper
     */
    public function __construct(
        DiscountEntityManagerInterface $discountEntityManager,
        DiscountMapperInterface $discountMapper
    ) {
        $this->discountEntityManager = $discountEntityManager;
        $this->discountMapper = $discountMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function createDiscountAmounts(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountConfiguratorTransfer
    {
        if ($discountConfiguratorTransfer->getDiscountCalculatorOrFail()->getCalculatorPluginOrFail() !== DiscountDependencyProvider::PLUGIN_CALCULATOR_FIXED) {
            return $discountConfiguratorTransfer;
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($discountConfiguratorTransfer) {
            return $this->executeCreateDiscountAmountsTransaction($discountConfiguratorTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function executeCreateDiscountAmountsTransaction(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountConfiguratorTransfer
    {
        $idDiscount = $discountConfiguratorTransfer->getDiscountGeneralOrFail()->getIdDiscountOrFail();
        $discountCalculatorTransfer = $discountConfiguratorTransfer->getDiscountCalculatorOrFail();

        foreach ($discountCalculatorTransfer->getMoneyValueCollection() as $moneyValueTransfer) {
            $discountMoneyAmountTransfer = $this->discountMapper->mapMoneyValueTransferToDiscountMoneyAmountTransfer(
                $moneyValueTransfer,
                (new DiscountMoneyAmountTransfer())->setFkDiscount($idDiscount),
            );

            $discountMoneyAmountTransfer = $this->discountEntityManager->createDiscountAmount($discountMoneyAmountTransfer);

            $this->discountMapper->mapDiscountMoneyAmountTransferToMoneyValueTransfer(
                $discountMoneyAmountTransfer,
                $moneyValueTransfer,
            );
        }

        return $discountConfiguratorTransfer;
    }
}
