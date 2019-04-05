<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderPaymentsRestApi\Business\OrderPayment;

use Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer;

class OrderPaymentUpdater implements OrderPaymentUpdaterInterface
{
    /**
     * @var \Spryker\Zed\OrderPaymentsRestApiExtension\Dependency\Plugin\OrderPaymentUpdaterPluginInterface[]
     */
    protected $orderPaymentUpdaterPlugins;

    /**
     * @param \Spryker\Zed\OrderPaymentsRestApiExtension\Dependency\Plugin\OrderPaymentUpdaterPluginInterface[] $orderPaymentUpdaterPlugins
     */
    public function __construct(array $orderPaymentUpdaterPlugins)
    {
        $this->orderPaymentUpdaterPlugins = $orderPaymentUpdaterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer
     */
    public function updateOrderPayment(
        UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
    ): UpdateOrderPaymentResponseTransfer {
        $updateOrderPaymentResponseTransfer = (new UpdateOrderPaymentResponseTransfer())->setIsSuccessful(false);

        foreach ($this->orderPaymentUpdaterPlugins as $orderPaymentUpdaterPlugin) {
            if ($orderPaymentUpdaterPlugin->isApplicable($updateOrderPaymentRequestTransfer)) {
                return $orderPaymentUpdaterPlugin->updateOrderPayment($updateOrderPaymentRequestTransfer);
            }
        }

        return $updateOrderPaymentResponseTransfer;
    }
}
