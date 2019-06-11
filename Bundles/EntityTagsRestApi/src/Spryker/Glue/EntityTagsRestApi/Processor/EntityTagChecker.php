<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Processor;

use Spryker\Glue\EntityTagsRestApi\EntityTagsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Symfony\Component\HttpFoundation\Request;

class EntityTagChecker implements EntityTagCheckerInterface
{
    /**
     * @var \Spryker\Glue\EntityTagsRestApi\EntityTagsRestApiConfig
     */
    protected $entityTagsRestApiConfig;

    /**
     * @param \Spryker\Glue\EntityTagsRestApi\EntityTagsRestApiConfig $entityTagsRestApiConfig
     */
    public function __construct(EntityTagsRestApiConfig $entityTagsRestApiConfig)
    {
        $this->entityTagsRestApiConfig = $entityTagsRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return bool
     */
    public function isEntityTagRequired(RestResourceInterface $restResource): bool
    {
        return in_array($restResource->getType(), $this->entityTagsRestApiConfig->getEntityTagRequiredResources());
    }

    /**
     * @param string $httpMethod
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return bool
     */
    public function isEntityTagValidationNeeded(string $httpMethod, RestResourceInterface $restResource): bool
    {
        return ($httpMethod === Request::METHOD_PATCH && $this->isEntityTagRequired($restResource));
    }

    /**
     * @param string $httpMethod
     *
     * @return bool
     */
    public function isMethodApplicableForAddingEntityTagHeader(string $httpMethod): bool
    {
        return in_array($httpMethod, [
            Request::METHOD_GET,
            Request::METHOD_POST,
            Request::METHOD_PATCH,
        ]);
    }
}
