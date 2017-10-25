<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Payment\Form\Filter;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Payment\PaymentClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;

class PaymentFormFilter
{

    /**
     * @var \Spryker\Client\Payment\PaymentClientInterface
     */
    protected $paymentClient;

    /**
     * @param \Spryker\Client\Payment\PaymentClientInterface $paymentClient
     */
    public function __construct(PaymentClientInterface $paymentClient)
    {
        $this->paymentClient = $paymentClient;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $formPluginCollection
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function filter(SubFormPluginCollection $formPluginCollection, AbstractTransfer $data)
    {
        if (!($data instanceof QuoteTransfer)) {
            return $formPluginCollection;
        }

        $availableMethods = $this->paymentClient->getAvailableMethods($data);

        //todo: use just remove function instead of re-creation
        $collection = new SubFormPluginCollection();

        foreach ($formPluginCollection as $formPlugin) {
            if ($this->containsMethod($availableMethods, $formPlugin->createSubForm()->getName())) {
                $collection->add($formPlugin);
            }
        }

        return $collection;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param string $paymentMethodName
     *
     * @return bool
     */
    protected function containsMethod(PaymentMethodsTransfer $paymentMethodsTransfer, $paymentMethodName)
    {
        foreach ($paymentMethodsTransfer->getAvailableMethods() as $availableMethod) {
            $name = $availableMethod->getProvider() . $availableMethod->getMethod();

            if ($name === $paymentMethodName) {
                return true;
            }
        }

        return false;
    }

}
