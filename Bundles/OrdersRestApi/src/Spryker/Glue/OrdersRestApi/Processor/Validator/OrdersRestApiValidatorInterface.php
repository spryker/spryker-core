<?php

namespace Spryker\Glue\OrdersRestApi\Processor\Validator;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface OrdersRestApiValidatorInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    public function isSameCustomerReference(RestRequestInterface $restRequest): bool;
}
