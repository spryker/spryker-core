<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Persistence;

use Generated\Shared\Transfer\ResourceShareTransfer;
use Orm\Zed\ResourceShare\Persistence\SpyResourceShare;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceSharePersistenceFactory getFactory()
 */
class ResourceShareEntityManager extends AbstractEntityManager implements ResourceShareEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer
     */
    public function createResourceShare(ResourceShareTransfer $resourceShareTransfer): ResourceShareTransfer
    {
        $resourceShareEntity = new SpyResourceShare();
        $resourceShareEntity->fromArray($resourceShareTransfer->toArray());
        $resourceShareEntity->save();

        return (new ResourceShareTransfer())
            ->fromArray($resourceShareEntity->toArray(), true);
    }
}
