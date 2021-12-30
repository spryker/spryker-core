<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Creator;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\Mapper\DiscountMapperInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginWithAmountInputTypeInterface;
use Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class DiscountAmountCreator implements DiscountAmountCreatorInterface
{
    use TransactionTrait;

    /**
     * @var array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface>
     */
    protected $discountCalculatorPlugins;

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
     * @param array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface> $discountCalculatorPlugins
     */
    public function __construct(
        DiscountEntityManagerInterface $discountEntityManager,
        DiscountMapperInterface $discountMapper,
        array $discountCalculatorPlugins
    ) {
        $this->discountEntityManager = $discountEntityManager;
        $this->discountMapper = $discountMapper;
        $this->discountCalculatorPlugins = $discountCalculatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function createDiscountAmounts(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountConfiguratorTransfer
    {
        $discountCalculatorPlugin = $this->getDiscountCalculatorPlugin($discountConfiguratorTransfer->getDiscountCalculator());

        if (
            !$discountCalculatorPlugin instanceof DiscountCalculatorPluginWithAmountInputTypeInterface
            || $discountCalculatorPlugin->getInputType() !== DiscountConstants::CALCULATOR_MONEY_INPUT_TYPE
        ) {
            return $discountConfiguratorTransfer;
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($discountConfiguratorTransfer) {
            return $this->executeCreateDiscountAmountsTransaction($discountConfiguratorTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountCalculatorTransfer $discountCalculatorTransfer
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface|null
     */
    protected function getDiscountCalculatorPlugin(DiscountCalculatorTransfer $discountCalculatorTransfer): ?DiscountCalculatorPluginInterface
    {
        return $this->discountCalculatorPlugins[$discountCalculatorTransfer->getCalculatorPlugin()] ?? null;
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
