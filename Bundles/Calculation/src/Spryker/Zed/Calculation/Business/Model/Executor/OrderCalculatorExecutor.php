<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Executor;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class OrderCalculatorExecutor implements OrderCalculatorExecutorInterface
{
    /**
     * @var array|\Spryker\Zed\CalculationExtension\Dependency\Plugin\CalculationPluginInterface[]
     */
    protected $orderCalculators;

    /**
     * @param \Spryker\Zed\CalculationExtension\Dependency\Plugin\CalculationPluginInterface[] $orderCalculators
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
    protected function mapCalculableObjectTransfer(OrderTransfer $orderTransfer)
    {
        $calculableObjectTransfer = new CalculableObjectTransfer();
        $calculableObjectTransfer->fromArray($orderTransfer->toArray(), true);
        $calculableObjectTransfer->setOriginalOrder($orderTransfer);

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function mapOrderTransfer(OrderTransfer $orderTransfer, CalculableObjectTransfer $calculableObjectTransfer)
    {
        $orderTransfer->fromArray($calculableObjectTransfer->toArray(), true);

        return $orderTransfer;
    }
}
