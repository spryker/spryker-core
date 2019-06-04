<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Processor;

use Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class EntityTagWriter implements EntityTagWriterInterface
{
    /**
     * @var \Spryker\Glue\EntityTagsRestApi\Processor\EntityTagCheckerInterface
     */
    protected $entityTagChecker;

    /**
     * @var \Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface
     */
    protected $entityTagClient;

    /**
     * @param \Spryker\Glue\EntityTagsRestApi\Processor\EntityTagCheckerInterface $entityTagChecker
     * @param \Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface $entityTagClient
     */
    public function __construct(EntityTagCheckerInterface $entityTagChecker, EntityTagsRestApiToEntityTagClientInterface $entityTagClient)
    {
        $this->entityTagChecker = $entityTagChecker;
        $this->entityTagClient = $entityTagClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return string|null
     */
    public function write(RestResourceInterface $restResource): ?string
    {
        if ($this->entityTagChecker->isEntityTagRequired($restResource)) {
            return $this->entityTagClient->write($restResource->getType(), $restResource->getId(), $restResource->getAttributes()->toArray());
        }

        return null;
    }
}
