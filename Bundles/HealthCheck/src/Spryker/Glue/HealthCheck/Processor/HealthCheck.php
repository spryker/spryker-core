<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\HealthCheck\Processor;

use Generated\Shared\Transfer\RestHealthCheckResponseAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\HealthCheck\HealthCheckConfig;
use Spryker\Glue\HealthCheck\Processor\Mapper\HealthCheckMapperInterface;
use Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface;

class HealthCheck implements HealthCheckInterface
{
    protected const KEY_HEALTH_CHECK_SERVICES = 'services';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface
     */
    protected $healthCheckProcessor;

    /**
     * @var \Spryker\Glue\HealthCheck\Processor\Mapper\HealthCheckMapperInterface
     */
    protected $healthCheckMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface $healthCheckProcessor
     * @param \Spryker\Glue\HealthCheck\Processor\Mapper\HealthCheckMapperInterface $healthCheckMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        HealthCheckProcessorInterface $healthCheckProcessor,
        HealthCheckMapperInterface $healthCheckMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->healthCheckProcessor = $healthCheckProcessor;
        $this->healthCheckMapper = $healthCheckMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function processHealthCheck(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $healthCheckResponseTransfer = $this->healthCheckProcessor->process(
            $restRequest->getHttpRequest()->query->get(static::KEY_HEALTH_CHECK_SERVICES)
        );

        $restHealthCheckResponseAttributesTransfer = $this->healthCheckMapper
            ->mapHealthCheckServiceResponseTransferToRestHealthCheckResponseAttributesTransfer(
                $healthCheckResponseTransfer,
                new RestHealthCheckResponseAttributesTransfer()
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            HealthCheckConfig::RESOURCE_HEALTH_CHECK,
            null,
            $restHealthCheckResponseAttributesTransfer
        );

        return $restResponse
            ->addResource($restResource)
            ->setStatus($healthCheckResponseTransfer->getStatusCode());
    }
}
