<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Controller\StorefrontApi\RestApi;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \SprykerFeature\Glue\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspServicesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getCollection": {
     *         "summary": [
     *             "Retrieves all booked services for the authenticated company user."
     *         ],
     *         "parameters": [{
     *             "ref": "acceptLanguage"
     *         }]
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        $servicesReader = $this->getFactory()->createServicesReader();

        return $servicesReader->getSspServices($restRequest);
    }
}
