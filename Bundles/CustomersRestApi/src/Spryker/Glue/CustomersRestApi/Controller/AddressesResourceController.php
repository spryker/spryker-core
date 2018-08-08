<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Controller;

use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CustomersRestApi\CustomersRestApiFactory getFactory()
 */
class AddressesResourceController extends AbstractController
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        if ($restRequest->findParentResourceByType(CustomersRestApiConfig::RESOURCE_CUSTOMERS)) {
            return $this->getFactory()
                ->createAddressesReader()
                ->readByCustomerReference(
                    $restRequest->findParentResourceByType(CustomersRestApiConfig::RESOURCE_CUSTOMERS)->getId()
                );
        }

        return $this->getFactory()
            ->createAddressesReader()
            ->readByUuid(
                $restRequest->getResource()->getId()
            );
    }
}
