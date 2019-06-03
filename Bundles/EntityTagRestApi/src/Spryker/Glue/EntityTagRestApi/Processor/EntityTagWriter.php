<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagRestApi\Processor;

use Spryker\Glue\EntityTagRestApi\Dependency\Client\EntityTagRestApiToEntityTagClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class EntityTagWriter implements EntityTagWriterInterface
{
    /**
     * @var \Spryker\Glue\EntityTagRestApi\Processor\EntityTagCheckerInterface
     */
    protected $entityTagChecker;

    /**
     * @var \Spryker\Glue\EntityTagRestApi\Dependency\Client\EntityTagRestApiToEntityTagClientInterface
     */
    protected $entityTagClient;

    /**
     * @param \Spryker\Glue\EntityTagRestApi\Processor\EntityTagCheckerInterface $entityTagChecker
     * @param \Spryker\Glue\EntityTagRestApi\Dependency\Client\EntityTagRestApiToEntityTagClientInterface $entityTagClient
     */
    public function __construct(EntityTagCheckerInterface $entityTagChecker, EntityTagRestApiToEntityTagClientInterface $entityTagClient)
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
    }
}
