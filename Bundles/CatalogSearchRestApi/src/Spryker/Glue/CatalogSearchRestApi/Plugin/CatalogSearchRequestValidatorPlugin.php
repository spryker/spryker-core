<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchRestApi\Plugin;

use Spryker\Glue\CatalogSearchRestApiExtension\Dependency\Plugin\CatalogSearchRequestValidatorPluginInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

/**
 * @method \Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiFactory getFactory()
 */
class CatalogSearchRequestValidatorPlugin extends AbstractPlugin implements CatalogSearchRequestValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates catalog search Rest API request.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface|null
     */
    public function validateIntegerParameters(RestRequestInterface $restRequest): ?RestResponseInterface
    {
        return $this->getFactory()
            ->createCatalogSearchRequestParametersValidator()
            ->validateIntegerParameters($restRequest);
    }
}
