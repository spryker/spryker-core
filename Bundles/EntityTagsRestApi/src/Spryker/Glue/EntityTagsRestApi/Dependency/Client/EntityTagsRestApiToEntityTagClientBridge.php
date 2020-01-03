<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Dependency\Client;

class EntityTagsRestApiToEntityTagClientBridge implements EntityTagsRestApiToEntityTagClientInterface
{
    /**
     * @var \Spryker\Client\EntityTag\EntityTagClientInterface
     */
    protected $entityTagClient;

    /**
     * @param \Spryker\Client\EntityTag\EntityTagClientInterface $entityTagClient
     */
    public function __construct($entityTagClient)
    {
        $this->entityTagClient = $entityTagClient;
    }

    /**
     * @param string $resourceName
     * @param string $resourceId
     * @param array $resourceAttributes
     *
     * @return string
     */
    public function write(string $resourceName, string $resourceId, array $resourceAttributes): string
    {
        return $this->entityTagClient->write($resourceName, $resourceId, $resourceAttributes);
    }

    /**
     * @param string $resourceName
     * @param string $resourceId
     *
     * @return string|null
     */
    public function read(string $resourceName, string $resourceId): ?string
    {
        return $this->entityTagClient->read($resourceName, $resourceId);
    }
}
