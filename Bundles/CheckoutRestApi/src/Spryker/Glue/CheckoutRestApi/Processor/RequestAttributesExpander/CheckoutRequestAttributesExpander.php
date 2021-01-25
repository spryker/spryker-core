<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerMapperInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CheckoutRequestAttributesExpander implements CheckoutRequestAttributesExpanderInterface
{
    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerMapperInterface
     */
    protected $customerMapper;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig
     */
    protected $config;

    /**
     * @var \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestExpanderPluginInterface[]
     */
    protected $checkoutRequestExpanderPlugins;

    /**
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerMapperInterface $customerMapper
     * @param \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig $config
     * @param \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestExpanderPluginInterface[] $checkoutRequestExpanderPlugins
     */
    public function __construct(
        CustomerMapperInterface $customerMapper,
        CheckoutRestApiConfig $config,
        array $checkoutRequestExpanderPlugins
    ) {
        $this->customerMapper = $customerMapper;
        $this->config = $config;
        $this->checkoutRequestExpanderPlugins = $checkoutRequestExpanderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function expandCheckoutRequestAttributes(
        RestRequestInterface $restRequest,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutRequestAttributesTransfer {
        $restCheckoutRequestAttributesTransfer = $this->expandCustomerData($restRequest, $restCheckoutRequestAttributesTransfer);
        $restCheckoutRequestAttributesTransfer = $this->expandPaymentSelection($restCheckoutRequestAttributesTransfer);

        return $this->executeCheckoutRequestExpanderPlugins($restRequest, $restCheckoutRequestAttributesTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function expandCustomerData(
        RestRequestInterface $restRequest,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutRequestAttributesTransfer {
        $restCustomerTransfer = $this->customerMapper->mapRestCustomerTransferFromRestCheckoutRequest($restRequest, $restCheckoutRequestAttributesTransfer);
        $restCheckoutRequestAttributesTransfer->setCustomer($restCustomerTransfer);

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function expandPaymentSelection(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutRequestAttributesTransfer {
        $payments = $restCheckoutRequestAttributesTransfer->getPayments();
        $paymentProviderMethodToStateMachineMapping = $this->config->getPaymentProviderMethodToStateMachineMapping();

        foreach ($payments as $payment) {
            if (isset($paymentProviderMethodToStateMachineMapping[$payment->getPaymentProviderName()][$payment->getPaymentMethodName()])) {
                $payment->setPaymentSelection($paymentProviderMethodToStateMachineMapping[$payment->getPaymentProviderName()][$payment->getPaymentMethodName()]);
            }
        }

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function executeCheckoutRequestExpanderPlugins(
        RestRequestInterface $restRequest,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutRequestAttributesTransfer {
        foreach ($this->checkoutRequestExpanderPlugins as $checkoutRequestExpanderPlugin) {
            $restCheckoutRequestAttributesTransfer = $checkoutRequestExpanderPlugin->expand(
                $restRequest,
                $restCheckoutRequestAttributesTransfer
            );
        }

        return $restCheckoutRequestAttributesTransfer;
    }
}
