<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\PaymentsRestApi\Processor\Expander\PaymentMethodsByCheckoutDataExpander;
use Spryker\Glue\PaymentsRestApi\Processor\Expander\PaymentMethodsByCheckoutDataExpanderInterface;
use Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodsMapper;
use Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodsMapperInterface;
use Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodsRestResponseBuilder;
use Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodsRestResponseBuilderInterface;

/**
 * @method \Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig getConfig()
 */
class PaymentsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\PaymentsRestApi\Processor\Expander\PaymentMethodsByCheckoutDataExpanderInterface
     */
    public function createPaymentMethodsByCheckoutDataExpander(): PaymentMethodsByCheckoutDataExpanderInterface
    {
        return new PaymentMethodsByCheckoutDataExpander(
            $this->createPaymentMethodsRestResponseBuilder(),
            $this->createPaymentMethodsMapper()
        );
    }

    /**
     * @return \Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodsMapperInterface
     */
    public function createPaymentMethodsMapper(): PaymentMethodsMapperInterface
    {
        return new PaymentMethodsMapper($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodsRestResponseBuilderInterface
     */
    public function createPaymentMethodsRestResponseBuilder(): PaymentMethodsRestResponseBuilderInterface
    {
        return new PaymentMethodsRestResponseBuilder($this->getResourceBuilder());
    }
}
