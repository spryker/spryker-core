<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ResourceShareTransfer;
use Orm\Zed\ResourceShare\Persistence\SpyResourceShare;

class ResourceShareMapper
{
    /**
     * @param \Orm\Zed\ResourceShare\Persistence\SpyResourceShare $resourceShareEntity
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer
     */
    public function mapResourceShareEntityToResourceShareTransfer(SpyResourceShare $resourceShareEntity): ResourceShareTransfer
    {
        return (new ResourceShareTransfer())->fromArray($resourceShareEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Orm\Zed\ResourceShare\Persistence\SpyResourceShare
     */
    public function mapResourceShareTransferToResourceShareEntity(ResourceShareTransfer $resourceShareTransfer): SpyResourceShare
    {
        $resourceShareEntity = new SpyResourceShare();
        $resourceShareEntity->fromArray($resourceShareTransfer->toArray());

        return $resourceShareEntity;
    }
}
