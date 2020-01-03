<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Processor\EntityTag;

use Spryker\Glue\EntityTagsRestApi\EntityTagsRestApiConfig;
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
     * @param string $httpMethod
     * @param string $resourceType
     *
     * @return bool
     */
    public function isEntityTagValidationNeeded(string $httpMethod, string $resourceType): bool
    {
        return ($httpMethod === Request::METHOD_PATCH && $this->isEntityTagRequired($resourceType));
    }

    /**
     * @param string $httpMethod
     * @param string $resourceType
     *
     * @return bool
     */
    public function isMethodApplicableForAddingEntityTagHeader(string $httpMethod, string $resourceType): bool
    {
        return (
            $this->isEntityTagRequired($resourceType) && in_array($httpMethod, [
                Request::METHOD_GET,
                Request::METHOD_POST,
                Request::METHOD_PATCH,
            ])
        );
    }

    /**
     * @param string $resourceType
     *
     * @return bool
     */
    protected function isEntityTagRequired(string $resourceType): bool
    {
        return in_array($resourceType, $this->entityTagsRestApiConfig->getEntityTagRequiredResources());
    }
}
