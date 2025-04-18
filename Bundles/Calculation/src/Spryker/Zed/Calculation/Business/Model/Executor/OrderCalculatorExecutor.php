<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Executor;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class OrderCalculatorExecutor implements OrderCalculatorExecutorInterface
{
    /**
     * @var array<\Spryker\Zed\CalculationExtension\Dependency\Plugin\CalculationPluginInterface>
     */
    protected $orderCalculators;

    /**
     * @param array<\Spryker\Zed\CalculationExtension\Dependency\Plugin\CalculationPluginInterface> $orderCalculators
     */
    public function __construct(array $orderCalculators)
    {
        $this->orderCalculators = $orderCalculators;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function recalculate(OrderTransfer $orderTransfer)
    {
        $calculableObjectTransfer = $this->mapCalculableObjectTransfer($orderTransfer);

        foreach ($this->orderCalculators as $calculator) {
            $calculator->recalculate($calculableObjectTransfer);
        }

        $orderTransfer = $this->mapOrderTransfer($calculableObjectTransfer->getOriginalOrder(), $calculableObjectTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function mapCalculableObjectTransfer(OrderTransfer $orderTransfer): CalculableObjectTransfer
    {
        $itemTransfers = $orderTransfer->getItems();
        // speedups next fromArray() execution
        $orderTransfer->setItems(new ArrayObject());

        $calculableObjectTransfer = (new CalculableObjectTransfer())
            ->fromArray($orderTransfer->toArray(), true)
            ->setItems($itemTransfers)
            ->setStore((new StoreTransfer())->setName($orderTransfer->getStore()))
            ->setOriginalOrder($orderTransfer);

        $orderTransfer->setItems($itemTransfers);

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function mapOrderTransfer(OrderTransfer $orderTransfer, CalculableObjectTransfer $calculableObjectTransfer): OrderTransfer
    {
        $itemTransfers = $calculableObjectTransfer->getItems();
        // speedups next fromArray() execution
        $calculableObjectTransfer->setItems(new ArrayObject());

        $orderTransfer
            ->fromArray($calculableObjectTransfer->toArray(), true)
            ->setStore($calculableObjectTransfer->getStore()->getName())
            ->setItems($itemTransfers);

        return $orderTransfer;
    }
}
