<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Updater;

use Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class DiscountUpdateAggregator implements DiscountUpdateAggregatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorInterface
     */
    protected $discountConfiguratorValidator;

    /**
     * @var \Spryker\Zed\Discount\Business\Updater\DiscountUpdaterInterface
     */
    protected $discountUpdater;

    /**
     * @var \Spryker\Zed\Discount\Business\Updater\DiscountAmountUpdaterInterface
     */
    protected $discountAmountUpdater;

    /**
     * @var \Spryker\Zed\Discount\Business\Updater\DiscountStoreUpdaterInterface
     */
    protected $discountStoreUpdater;

    /**
     * @var array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountPostUpdatePluginInterface>
     */
    protected $discountPostUpdatePlugins;

    /**
     * @param \Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorInterface $discountConfiguratorValidator
     * @param \Spryker\Zed\Discount\Business\Updater\DiscountUpdaterInterface $discountUpdater
     * @param \Spryker\Zed\Discount\Business\Updater\DiscountAmountUpdaterInterface $discountAmountUpdater
     * @param \Spryker\Zed\Discount\Business\Updater\DiscountStoreUpdaterInterface $discountStoreUpdater
     * @param array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountPostUpdatePluginInterface> $discountPostUpdatePlugins
     */
    public function __construct(
        DiscountConfiguratorValidatorInterface $discountConfiguratorValidator,
        DiscountUpdaterInterface $discountUpdater,
        DiscountAmountUpdaterInterface $discountAmountUpdater,
        DiscountStoreUpdaterInterface $discountStoreUpdater,
        array $discountPostUpdatePlugins
    ) {
        $this->discountConfiguratorValidator = $discountConfiguratorValidator;
        $this->discountUpdater = $discountUpdater;
        $this->discountAmountUpdater = $discountAmountUpdater;
        $this->discountStoreUpdater = $discountStoreUpdater;
        $this->discountPostUpdatePlugins = $discountPostUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer
     */
    public function updateDiscountWithValidation(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountConfiguratorResponseTransfer
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
            return $this->executeUpdateDiscountWithValidationTransaction($discountConfiguratorTransfer, $discountConfiguratorResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     * @param \Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer $discountConfiguratorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer
     */
    protected function executeUpdateDiscountWithValidationTransaction(
        DiscountConfiguratorTransfer $discountConfiguratorTransfer,
        DiscountConfiguratorResponseTransfer $discountConfiguratorResponseTransfer
    ): DiscountConfiguratorResponseTransfer {
        $this->discountUpdater->updateDiscount($discountConfiguratorTransfer);
        $this->discountStoreUpdater->updateDiscountStoreRelationships($discountConfiguratorTransfer);
        $discountConfiguratorTransfer = $this->discountAmountUpdater->updateDiscountAmounts($discountConfiguratorTransfer);

        $discountConfiguratorTransfer = $this->executeDiscountPostUpdatePlugins($discountConfiguratorTransfer);

        return $discountConfiguratorResponseTransfer
            ->setDiscountConfigurator($discountConfiguratorTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function executeDiscountPostUpdatePlugins(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountConfiguratorTransfer
    {
        foreach ($this->discountPostUpdatePlugins as $discountPostUpdatePlugin) {
            $discountConfiguratorTransfer = $discountPostUpdatePlugin->postUpdate($discountConfiguratorTransfer);
        }

        return $discountConfiguratorTransfer;
    }
}
