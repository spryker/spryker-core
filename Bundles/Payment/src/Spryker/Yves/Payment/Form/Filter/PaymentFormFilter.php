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

class PaymentFormFilter implements PaymentFormFilterInterface
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

        $paymentMethodsTransfer = $this->paymentClient->getAvailableMethods($data);
        $formPluginCollection = $this->filterSubFormPluginCollection($formPluginCollection, $paymentMethodsTransfer);

        return $formPluginCollection;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $subFormPluginCollection
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    protected function filterSubFormPluginCollection(
        SubFormPluginCollection $subFormPluginCollection,
        PaymentMethodsTransfer $paymentMethodsTransfer
    ) {
        $collection = new SubFormPluginCollection();

        foreach ($subFormPluginCollection as $subFormPlugin) {
            $subFormName = $subFormPlugin->createSubForm()->getName();

            if ($this->containsMethod($paymentMethodsTransfer, $subFormName)) {
                $collection->add($subFormPlugin);
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
        foreach ($paymentMethodsTransfer->getMethods() as $availableMethod) {
            if ($availableMethod->getMethodName() === $paymentMethodName) {
                return true;
            }
        }

        return false;
    }
}
