<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Processor;

use Spryker\Glue\EntityTagsRestApi\EntityTagsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

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
}
