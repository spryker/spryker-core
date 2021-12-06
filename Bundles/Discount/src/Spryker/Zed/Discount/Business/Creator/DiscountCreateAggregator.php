<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Creator;

use Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class DiscountCreateAggregator implements DiscountCreateAggregatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorInterface
     */
    protected $discountConfiguratorValidator;

    /**
     * @var \Spryker\Zed\Discount\Business\Creator\DiscountCreatorInterface
     */
    protected $discountCreator;

    /**
     * @var \Spryker\Zed\Discount\Business\Creator\DiscountAmountCreatorInterface
     */
    protected $discountAmountCreator;

    /**
     * @var \Spryker\Zed\Discount\Business\Creator\DiscountStoreCreatorInterface
     */
    protected $discountStoreCreator;

    /**
     * @var array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountPostCreatePluginInterface>
     */
    protected $discountPostCreatePlugins;

    /**
     * @param \Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorInterface $discountConfiguratorValidator
     * @param \Spryker\Zed\Discount\Business\Creator\DiscountCreatorInterface $discountCreator
     * @param \Spryker\Zed\Discount\Business\Creator\DiscountAmountCreatorInterface $discountAmountCreator
     * @param \Spryker\Zed\Discount\Business\Creator\DiscountStoreCreatorInterface $discountStoreCreator
     * @param array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountPostCreatePluginInterface> $discountPostCreatePlugins
     */
    public function __construct(
        DiscountConfiguratorValidatorInterface $discountConfiguratorValidator,
        DiscountCreatorInterface $discountCreator,
        DiscountAmountCreatorInterface $discountAmountCreator,
        DiscountStoreCreatorInterface $discountStoreCreator,
        array $discountPostCreatePlugins
    ) {
        $this->discountConfiguratorValidator = $discountConfiguratorValidator;
        $this->discountCreator = $discountCreator;
        $this->discountAmountCreator = $discountAmountCreator;
        $this->discountStoreCreator = $discountStoreCreator;
        $this->discountPostCreatePlugins = $discountPostCreatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer
     */
    public function createDiscount(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountConfiguratorResponseTransfer
    {
        $discountConfiguratorResponseTransfer = (new DiscountConfiguratorResponseTransfer())->setDiscountConfigurator($discountConfiguratorTransfer);
        $discountConfiguratorResponseTransfer = $this->discountConfiguratorValidator->validateDiscountConfigurator(
            $discountConfiguratorTransfer,
            $discountConfiguratorResponseTransfer,
        );

        if (!$discountConfiguratorResponseTransfer->getIsSuccessfulOrFail()) {
            return $discountConfiguratorResponseTransfer;
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($discountConfiguratorTransfer, $discountConfiguratorResponseTransfer) {
            return $this->executeCreateDiscountTransaction($discountConfiguratorTransfer, $discountConfiguratorResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     * @param \Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer $discountConfiguratorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer
     */
    protected function executeCreateDiscountTransaction(
        DiscountConfiguratorTransfer $discountConfiguratorTransfer,
        DiscountConfiguratorResponseTransfer $discountConfiguratorResponseTransfer
    ): DiscountConfiguratorResponseTransfer {
        $discountConfiguratorTransfer = $this->discountCreator->createDiscount($discountConfiguratorTransfer);
        $discountConfiguratorTransfer = $this->discountAmountCreator->createDiscountAmounts($discountConfiguratorTransfer);
        $this->discountStoreCreator->createDiscountStoreRelationships($discountConfiguratorTransfer);

        $discountConfiguratorTransfer = $this->executeDiscountPostCreatePlugins($discountConfiguratorTransfer);

        return $discountConfiguratorResponseTransfer
            ->setDiscountConfigurator($discountConfiguratorTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function executeDiscountPostCreatePlugins(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountConfiguratorTransfer
    {
        foreach ($this->discountPostCreatePlugins as $discountPostCreatePlugin) {
            $discountConfiguratorTransfer = $discountPostCreatePlugin->postCreate($discountConfiguratorTransfer);
        }

        return $discountConfiguratorTransfer;
    }
}
