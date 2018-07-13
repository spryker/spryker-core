<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\Controller\AbstractRestController;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

/**
 * @method \Spryker\Glue\SearchRestApi\SearchRestApiFactory getFactory()
 */
class SearchResourceController extends AbstractRestController
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(): RestResponseInterface
    {
        $restRequest = $this->getRestRequest();

        return $this->getFactory()->createCatalogReader()->catalogSearch($restRequest);
    }
}
