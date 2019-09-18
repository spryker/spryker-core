<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\PaymentsRestApi\Processor\Expander\PaymentMethodByCheckoutDataExpander;
use Spryker\Glue\PaymentsRestApi\Processor\Expander\PaymentMethodByCheckoutDataExpanderInterface;
use Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodMapper;
use Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodMapperInterface;
use Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodsRestResponseBuilder;
use Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodsRestResponseBuilderInterface;

/**
 * @method \Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig getConfig()
 */
class PaymentsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\PaymentsRestApi\Processor\Expander\PaymentMethodByCheckoutDataExpanderInterface
     */
    public function createPaymentMethodByCheckoutDataExpander(): PaymentMethodByCheckoutDataExpanderInterface
    {
        return new PaymentMethodByCheckoutDataExpander(
            $this->createPaymentMethodsRestResponseBuilder(),
            $this->createPaymentMethodsMapper()
        );
    }

    /**
     * @return \Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodMapperInterface
     */
    public function createPaymentMethodsMapper(): PaymentMethodMapperInterface
    {
        return new PaymentMethodMapper($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodsRestResponseBuilderInterface
     */
    public function createPaymentMethodsRestResponseBuilder(): PaymentMethodsRestResponseBuilderInterface
    {
        return new PaymentMethodsRestResponseBuilder($this->getResourceBuilder());
    }
}
