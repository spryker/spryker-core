<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Business\Resolver;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig;

class PaymentMethodResolver implements PaymentMethodResolverInterface
{
    /**
     * @param \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig $config
     */
    public function __construct(protected SspServiceManagementConfig $config)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function resolvePaymentMethod(ItemTransfer $itemTransfer, PaymentTransfer $paymentTransfer): string
    {
        $paymentMethodStateMachineMapping = $this->config->getPaymentMethodStatemachineProcessMapping();

        $itemPaymentMehtod = '';

        foreach ($paymentMethodStateMachineMapping as $paymentMethod => $processName) {
            if ($itemTransfer->getProcess() !== $processName) {
                continue;
            }

            if (!$itemPaymentMehtod || $this->hasSimilarName($paymentTransfer->getPaymentMethodOrFail(), $paymentMethod)) {
                $itemPaymentMehtod = $paymentMethod;
            }
        }

        return $itemPaymentMehtod;
    }

    /**
     * @param string $orderPaymentMethodName
     * @param string $paymentMethodName
     *
     * @return bool
     */
    protected function hasSimilarName(string $orderPaymentMethodName, string $paymentMethodName): bool
    {
        $matches = [];

        preg_match('/^([\w]+)/', $orderPaymentMethodName, $matches);

        if (!isset($matches[0])) {
            return false;
        }

        return strpos(strtolower($paymentMethodName), strtolower($matches[0])) !== false;
    }
}
