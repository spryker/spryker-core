<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Cors;

use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface;

class CorsResponse implements CorsResponseInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface
     */
    protected $resourceRouteLoader;

    /**
     * @var \Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface $resourceRouteLoader
     * @param \Spryker\Glue\GlueApplication\GlueApplicationConfig $config
     */
    public function __construct(
        ResourceRouteLoaderInterface $resourceRouteLoader,
        GlueApplicationConfig $config
    ) {
        $this->resourceRouteLoader = $resourceRouteLoader;
        $this->config = $config;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addCorsHeaders(RestRequestInterface $restRequest, RestResponseInterface $restResponse): RestResponseInterface
    {
        $availableMethods = $this->resourceRouteLoader
            ->getAvailableMethods(
                $restRequest->getResource()->getType(),
                $restRequest->getHttpRequest()
            );

        $restResponse->addHeader(RequestConstantsInterface::HEADER_ACCESS_CONTROL_ALLOW_METHODS, implode(', ', $availableMethods));
        $restResponse->addHeader(RequestConstantsInterface::HEADER_ACCESS_CONTROL_ALLOW_HEADERS, implode(', ', $this->config->getCorsAllowedHeaders()));

        return $restResponse;
    }
}
