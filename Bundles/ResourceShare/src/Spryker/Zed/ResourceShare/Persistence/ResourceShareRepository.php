<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Persistence;

use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceSharePersistenceFactory getFactory()
 */
class ResourceShareRepository extends AbstractRepository implements ResourceShareRepositoryInterface
{
    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer|null
     */
    public function findResourceShareByUuid(string $uuid): ?ResourceShareTransfer
    {
        $resourceShareEntity = $this->getFactory()
            ->createResourceSharePropelQuery()
            ->filterByUuid($uuid)
            ->findOne();

        if (!$resourceShareEntity) {
            return null;
        }

        return $this->getFactory()
            ->createResourceShareMapper()
            ->mapResourceShareEntityToResourceShareTransfer($resourceShareEntity);
    }
}
