<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCollectionTransfer;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class PublishAndSynchronizeHealthCheckMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheck> $publishAndSynchronizeHealthCheckEntities
     * @param \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCollectionTransfer $publishAndSynchronizeHealthCheckCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCollectionTransfer
     */
    public function mapPublishAndSynchronizeHealthCheckEntitiesToPublishAndSynchronizeHealthCheckCollectionTransfer(
        ObjectCollection $publishAndSynchronizeHealthCheckEntities,
        PublishAndSynchronizeHealthCheckCollectionTransfer $publishAndSynchronizeHealthCheckCollectionTransfer
    ): PublishAndSynchronizeHealthCheckCollectionTransfer {
        /** @var \Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\Base\SpyPublishAndSynchronizeHealthCheck $publishAndSynchronizeHealthCheckEntity */
        foreach ($publishAndSynchronizeHealthCheckEntities as $publishAndSynchronizeHealthCheckEntity) {
            $publishAndSynchronizeHealthCheckCollectionTransfer->addPublishAndSynchronizeHealthCheck(
                (new PublishAndSynchronizeHealthCheckTransfer())
                    ->fromArray($publishAndSynchronizeHealthCheckEntity->toArray(), true),
            );
        }

        return $publishAndSynchronizeHealthCheckCollectionTransfer;
    }
}
