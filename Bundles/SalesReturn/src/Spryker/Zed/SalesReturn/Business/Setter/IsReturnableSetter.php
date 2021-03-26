<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Setter;

use DateTime;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\SalesReturn\Dependency\Service\SalesReturnToUtilDateTimeServiceInterface;
use Spryker\Zed\SalesReturn\SalesReturnConfig;

class IsReturnableSetter implements IsReturnableSetterInterface
{
    protected const GLOSSARY_KEY_RETURNABLE_TILL_DATE = 'return.return_policy.returnable_till.message';
    protected const GLOSSARY_PARAMETER_RETURNABLE_TILL_DATE = '%date%';

    /**
     * @var \Spryker\Zed\SalesReturn\SalesReturnConfig
     */
    protected $salesReturnConfig;

    /**
     * @var \Spryker\Zed\SalesReturn\Dependency\Service\SalesReturnToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @param \Spryker\Zed\SalesReturn\SalesReturnConfig $salesReturnConfig
     * @param \Spryker\Zed\SalesReturn\Dependency\Service\SalesReturnToUtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(
        SalesReturnConfig $salesReturnConfig,
        SalesReturnToUtilDateTimeServiceInterface $utilDateTimeService
    ) {
        $this->salesReturnConfig = $salesReturnConfig;
        $this->utilDateTimeService = $utilDateTimeService;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function setOrderItemIsReturnableByGlobalReturnableNumberOfDays(array $itemTransfers): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            if ($this->isOrderItemPassedGlobalReturnableNumberOfDays($itemTransfer)) {
                $itemTransfer->setIsReturnable(false);
            }

            $this->addReturnPolicyMessage($itemTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addReturnPolicyMessage(ItemTransfer $itemTransfer): void
    {
        if (!$itemTransfer->getCreatedAt()) {
            return;
        }

        $returnableTillDateTime = (new DateTime($itemTransfer->getCreatedAt() ?? ''))
            ->modify('+' . $this->salesReturnConfig->getGlobalReturnableNumberOfDays() . ' days');

        $formattedReturnableTillDateTime = $this->utilDateTimeService->formatDate($returnableTillDateTime);

        $messageTransfer = (new MessageTransfer())
            ->setValue(static::GLOSSARY_KEY_RETURNABLE_TILL_DATE)
            ->setParameters([
                static::GLOSSARY_PARAMETER_RETURNABLE_TILL_DATE => $formattedReturnableTillDateTime,
            ]);

        $itemTransfer->addReturnPolicyMessage($messageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function setOrderItemIsReturnableByItemState(array $itemTransfers): array
    {
        $returnableStateNames = $this->salesReturnConfig->getReturnableStateNames();

        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer
                ->requireState()
                ->getStateOrFail()
                    ->requireName();

            if (!in_array($itemTransfer->getStateOrFail()->getName(), $returnableStateNames, true)) {
                $itemTransfer->setIsReturnable(false);
            }
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isOrderItemPassedGlobalReturnableNumberOfDays(ItemTransfer $itemTransfer): bool
    {
        if (!$itemTransfer->getCreatedAt()) {
            return true;
        }

        $currentDate = (new DateTime())->setTime(0, 0);
        $createdAt = (new DateTime($itemTransfer->getCreatedAt() ?? ''))->setTime(0, 0);

        return $currentDate->diff($createdAt)->days > $this->salesReturnConfig->getGlobalReturnableNumberOfDays();
    }
}
