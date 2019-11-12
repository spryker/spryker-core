<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\HealthCheckRestApi\Processor\HealthCheck;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\RestHealthCheckResponseAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\HealthCheckRestApi\Dependency\Service\HealthCheckRestApiToHealthCheckServiceInterface;
use Spryker\Glue\HealthCheckRestApi\HealthCheckRestApiConfig;
use Spryker\Glue\HealthCheckRestApi\Processor\Mapper\HealthCheckMapperInterface;

class HealthCheckProcessor implements HealthCheckProcessorInterface
{
    protected const KEY_SERVICES = 'services';

    /**
     * @var \Spryker\Glue\HealthCheckRestApi\Dependency\Service\HealthCheckRestApiToHealthCheckServiceInterface
     */
    protected $healthCheckService;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\HealthCheckRestApi\Processor\Mapper\HealthCheckMapperInterface
     */
    protected $healthCheckMapper;

    /**
     * @var \Spryker\Glue\HealthCheckRestApi\HealthCheckRestApiConfig
     */
    protected $healthCheckRestApiConfig;

    /**
     * @param \Spryker\Glue\HealthCheckRestApi\Dependency\Service\HealthCheckRestApiToHealthCheckServiceInterface $healthCheckService
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\HealthCheckRestApi\Processor\Mapper\HealthCheckMapperInterface $healthCheckMapper
     * @param \Spryker\Glue\HealthCheckRestApi\HealthCheckRestApiConfig $healthCheckRestApiConfig
     */
    public function __construct(
        HealthCheckRestApiToHealthCheckServiceInterface $healthCheckService,
        RestResourceBuilderInterface $restResourceBuilder,
        HealthCheckMapperInterface $healthCheckMapper,
        HealthCheckRestApiConfig $healthCheckRestApiConfig
    ) {
        $this->healthCheckService = $healthCheckService;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->healthCheckMapper = $healthCheckMapper;
        $this->healthCheckRestApiConfig = $healthCheckRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function processHealthCheck(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $services = $restRequest->getHttpRequest()->get(static::KEY_SERVICES);

        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setServices($services);

        $healthCheckResponseTransfer = $this->healthCheckService->checkGlueHealthCheck($healthCheckRequestTransfer);

        $restHealthCheckResponseAttributesTransfer = $this->healthCheckMapper
            ->mapHealthCheckServiceResponseTransferToRestHealthCheckResponseAttributesTransfer(
                $healthCheckResponseTransfer,
                new RestHealthCheckResponseAttributesTransfer()
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            HealthCheckRestApiConfig::RESOURCE_HEALTH_CHECK,
            null,
            $restHealthCheckResponseAttributesTransfer
        );

        return $restResponse->addResource($restResource);
    }
}
