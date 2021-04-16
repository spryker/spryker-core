<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Validator;

use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartsRestApiValidator implements CartsRestApiValidatorInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    public function isSameCustomerReference(RestRequestInterface $restRequest): bool
    {
        $restUser = $restRequest->getRestUser();
        if ($restUser === null) {
            return false;
        }

        $customerResource = $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_CUSTOMERS);

        if ($customerResource === null) {
            return false;
        }

        return $restUser->getNaturalIdentifier() === $customerResource->getId();
    }
}
