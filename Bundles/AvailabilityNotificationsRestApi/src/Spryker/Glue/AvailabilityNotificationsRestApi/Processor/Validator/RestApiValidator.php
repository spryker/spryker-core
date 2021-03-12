<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Validator;

use Spryker\Glue\AvailabilityNotificationsRestApi\AvailabilityNotificationsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class RestApiValidator implements RestApiValidatorInterface
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

        $customerResource = $restRequest->findParentResourceByType(AvailabilityNotificationsRestApiConfig::RESOURCE_CUSTOMERS) ?? $restRequest->getResource();

        return $restUser->getNaturalIdentifier() === $customerResource->getId();
    }
}
