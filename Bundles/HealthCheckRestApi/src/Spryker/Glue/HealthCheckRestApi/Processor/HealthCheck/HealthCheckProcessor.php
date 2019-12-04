<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\HealthCheckRestApi\Processor\HealthCheck;

use Generated\Shared\Transfer\RestHealthCheckResponseAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\HealthCheckRestApi\Dependency\Client\HealthCheckRestApiToHealthCheckClientInterface;
use Spryker\Glue\HealthCheckRestApi\HealthCheckRestApiConfig;
use Spryker\Glue\HealthCheckRestApi\Processor\Mapper\HealthCheckMapperInterface;

class HealthCheckProcessor implements HealthCheckProcessorInterface
{
    protected const KEY_HEALTH_CHECK_SERVICES = 'services';

    /**
     * @var \Spryker\Glue\HealthCheckRestApi\Dependency\Client\HealthCheckRestApiToHealthCheckClientInterface
     */
    protected $healthCheckClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\HealthCheckRestApi\Processor\Mapper\HealthCheckMapperInterface
     */
    protected $healthCheckMapper;

    /**
     * @var \Spryker\Glue\HealthCheckRestApi\Processor\HealthCheck\HealthCheckRequestFormatterInterface
     */
    protected $healthCheckRequestFormatter;

    /**
     * @param \Spryker\Glue\HealthCheckRestApi\Dependency\Client\HealthCheckRestApiToHealthCheckClientInterface $healthCheckClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\HealthCheckRestApi\Processor\Mapper\HealthCheckMapperInterface $healthCheckMapper
     * @param \Spryker\Glue\HealthCheckRestApi\Processor\HealthCheck\HealthCheckRequestFormatterInterface $healthCheckRequestFormatter
     */
    public function __construct(
        HealthCheckRestApiToHealthCheckClientInterface $healthCheckClient,
        RestResourceBuilderInterface $restResourceBuilder,
        HealthCheckMapperInterface $healthCheckMapper,
        HealthCheckRequestFormatterInterface $healthCheckRequestFormatter
    ) {
        $this->healthCheckClient = $healthCheckClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->healthCheckMapper = $healthCheckMapper;
        $this->healthCheckRequestFormatter = $healthCheckRequestFormatter;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function processHealthCheck(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $healthCheckRequestTransfer = $this->healthCheckRequestFormatter->getHealthCheckRequestTransfer($restRequest);
        $healthCheckResponseTransfer = $this->healthCheckClient->executeHealthCheck($healthCheckRequestTransfer);

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

        return $restResponse
            ->addResource($restResource)
            ->setStatus($healthCheckResponseTransfer->getStatusCode());
    }
}
