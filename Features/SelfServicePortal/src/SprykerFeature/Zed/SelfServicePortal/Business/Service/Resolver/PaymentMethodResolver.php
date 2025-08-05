<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Resolver;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class PaymentMethodResolver implements PaymentMethodResolverInterface
{
    public function __construct(protected SelfServicePortalConfig $config)
    {
    }

    public function resolvePaymentMethod(ItemTransfer $itemTransfer, PaymentTransfer $paymentTransfer): string
    {
        $paymentMethodStateMachineMapping = $this->config->getPaymentMethodStateMachineProcessMapping();

        $itemPaymentMethod = '';

        foreach ($paymentMethodStateMachineMapping as $paymentMethod => $processName) {
            if ($itemTransfer->getProcess() !== $processName) {
                continue;
            }

            if (!$itemPaymentMethod || $this->hasSimilarName($paymentTransfer->getPaymentMethodOrFail(), $paymentMethod)) {
                $itemPaymentMethod = $paymentMethod;
            }
        }

        return $itemPaymentMethod;
    }

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
