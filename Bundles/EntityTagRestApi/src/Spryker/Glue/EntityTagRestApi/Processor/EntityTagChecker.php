<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagRestApi\Processor;

use Spryker\Glue\EntityTagRestApi\EntityTagRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class EntityTagChecker implements EntityTagCheckerInterface
{
    /**
     * @var \Spryker\Glue\EntityTagRestApi\EntityTagRestApiConfig
     */
    protected $entityTagRestApiConfig;

    /**
     * @param \Spryker\Glue\EntityTagRestApi\EntityTagRestApiConfig $entityTagRestApiConfig
     */
    public function __construct(EntityTagRestApiConfig $entityTagRestApiConfig)
    {
        $this->entityTagRestApiConfig = $entityTagRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return bool
     */
    public function isEntityTagRequired(RestResourceInterface $restResource): bool
    {
        return in_array($restResource->getType(), $this->entityTagRestApiConfig->getEntityTagRequiredResources());
    }
}
