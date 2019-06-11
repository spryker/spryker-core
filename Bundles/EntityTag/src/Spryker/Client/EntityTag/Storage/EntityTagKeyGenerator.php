<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\EntityTag\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\EntityTag\Dependency\Service\EntityTagToSynchronizationServiceInterface;
use Spryker\Client\EntityTag\EntityTagConfig;

class EntityTagKeyGenerator implements EntityTagKeyGeneratorInterface
{
    /**
     * @var \Spryker\Client\EntityTag\Dependency\Service\EntityTagToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\EntityTag\Dependency\Service\EntityTagToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(EntityTagToSynchronizationServiceInterface $synchronizationService)
    {
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $resourceName
     * @param string $resourceId
     *
     * @return string
     */
    public function generate(string $resourceName, string $resourceId): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setKey($resourceName)
            ->setReference($resourceId);

        return $this->synchronizationService
            ->getStorageKeyBuilder(EntityTagConfig::ENTITY_TAG_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
