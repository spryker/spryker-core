<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\HealthCheckRestApi\Processor\HealthCheck;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\HealthCheckRestApi\Dependency\Service\HealthCheckRestApiToHealthCheckServiceInterface;
use Spryker\Glue\HealthCheckRestApi\HealthCheckRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class HealthCheckProcessor implements HealthCheckProcessorInterface
{
    protected const KEY_SERVICE = 'service';

    /**
     * @var \Spryker\Glue\HealthCheckRestApi\Dependency\Service\HealthCheckRestApiToHealthCheckServiceInterface
     */
    protected $healthCheckService;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\HealthCheckRestApi\HealthCheckRestApiConfig
     */
    protected $healthCheckRestApiConfig;

    /**
     * @param \Spryker\Glue\HealthCheckRestApi\Dependency\Service\HealthCheckRestApiToHealthCheckServiceInterface $healthCheckService
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\HealthCheckRestApi\HealthCheckRestApiConfig $healthCheckRestApiConfig
     */
    public function __construct(
        HealthCheckRestApiToHealthCheckServiceInterface $healthCheckService,
        RestResourceBuilderInterface $restResourceBuilder,
        HealthCheckRestApiConfig $healthCheckRestApiConfig
    ) {
        $this->healthCheckService = $healthCheckService;
        $this->restResourceBuilder = $restResourceBuilder;
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

//        if (!$this->healthCheckRestApiConfig->isHealthCheckEnabled()) {
//            return $restResponse->addError(
//                $this->createServicesAreDisable()
//            );
//        }

        $services = $restRequest->getQueryString([static::KEY_SERVICE]);

        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setApplication(APPLICATION)
            ->setServices($services);

        $healthCheckResponseTransfer = $this->healthCheckService->processHealthCheck($healthCheckRequestTransfer);

        $restResource = $this->restResourceBuilder->createRestResource(
            HealthCheckRestApiConfig::RESOURCE_HEALTH_CHECK,
            null,
            $healthCheckResponseTransfer
        );

        return $restResponse->addResource($restResource);
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createServicesAreDisable(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(HealthCheckRestApiConfig::RESPONSE_CODE_SERVICES_ARE_DISABLED)
            ->setStatus(Response::HTTP_SERVICE_UNAVAILABLE)
            ->setDetail(HealthCheckRestApiConfig::RESPONSE_DETAILS_SERVICES_ARE_DISABLED);
    }
}
