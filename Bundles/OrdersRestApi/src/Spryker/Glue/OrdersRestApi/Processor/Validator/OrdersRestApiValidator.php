<?php

namespace Spryker\Glue\OrdersRestApi\Processor\Validator;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OrdersRestApi\OrdersRestApiConfig;

class OrdersRestApiValidator implements OrdersRestApiValidatorInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    public function isSameCustomerReference(RestRequestInterface $restRequest): bool
    {
        $restUser = $restRequest->getRestUser();
        if (!$restUser) {
            return false;
        }

        $customerResource = $restRequest->findParentResourceByType(OrdersRestApiConfig::RESOURCE_CUSTOMERS) ?? $restRequest->getResource();

        return $restUser->getNaturalIdentifier() === $customerResource->getId();
    }
}
