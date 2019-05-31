<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagRestApi\Processor;

use Spryker\Glue\EntityTagRestApi\Dependency\Client\EntityTagRestApiToEntityTagClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class EntityTagResolver implements EntityTagResolverInterface
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
     * @var \Spryker\Glue\EntityTagRestApi\Processor\EntityTagWriterInterface
     */
    protected $entityTagWriter;

    /**
     * @param \Spryker\Glue\EntityTagRestApi\Processor\EntityTagCheckerInterface $entityTagChecker
     * @param \Spryker\Glue\EntityTagRestApi\Dependency\Client\EntityTagRestApiToEntityTagClientInterface $entityTagClient
     * @param \Spryker\Glue\EntityTagRestApi\Processor\EntityTagWriterInterface $entityTagWriter
     */
    public function __construct(
        EntityTagCheckerInterface $entityTagChecker,
        EntityTagRestApiToEntityTagClientInterface $entityTagClient,
        EntityTagWriterInterface $entityTagWriter
    ) {
        $this->entityTagChecker = $entityTagChecker;
        $this->entityTagClient = $entityTagClient;
        $this->entityTagWriter = $entityTagWriter;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return string|null
     */
    public function resolve(RestResourceInterface $restResource): ?string
    {
        if (!$this->entityTagChecker->isEntityTagRequired($restResource)) {
            return null;
        }

        $entityTag = $this->entityTagClient->read($restResource->getType(), $restResource->getId());

        if ($entityTag) {
            return $entityTag;
        }

        return $this->entityTagWriter->write($restResource);
    }
}
