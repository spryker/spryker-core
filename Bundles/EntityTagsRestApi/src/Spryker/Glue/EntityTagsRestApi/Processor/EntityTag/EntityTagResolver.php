<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Processor\EntityTag;

use Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class EntityTagResolver implements EntityTagResolverInterface
{
    /**
     * @var \Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagCheckerInterface
     */
    protected $entityTagChecker;

    /**
     * @var \Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface
     */
    protected $entityTagClient;

    /**
     * @param \Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagCheckerInterface $entityTagChecker
     * @param \Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface $entityTagClient
     */
    public function __construct(
        EntityTagCheckerInterface $entityTagChecker,
        EntityTagsRestApiToEntityTagClientInterface $entityTagClient
    ) {
        $this->entityTagChecker = $entityTagChecker;
        $this->entityTagClient = $entityTagClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return string|null
     */
    public function resolve(RestResourceInterface $restResource): ?string
    {
        $entityTag = $this->entityTagClient->read($restResource->getType(), $restResource->getId());

        if ($entityTag) {
            return $entityTag;
        }

        return $this->entityTagClient->write(
            $restResource->getType(),
            $restResource->getId(),
            $restResource->getAttributes()->toArray()
        );
    }
}
