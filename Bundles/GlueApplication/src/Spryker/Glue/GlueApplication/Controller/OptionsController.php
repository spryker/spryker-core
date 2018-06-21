<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class OptionsController extends AbstractRestController
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function resourceOptionsAction(): RestResponseInterface
    {
        $request = $this->getRestRequest();

        $availableMethods = $this->getFactory()
            ->createResourceRouteLoader()
            ->getAvailableMethods($request->getResource()->getType(), $request->getHttpRequest());

        $response = $this->getFactory()->getResourceBuilder()->createRestResponse();
        $response->addHeader('Allow', implode(', ', $availableMethods));

        return $response;
    }
}
