<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Business\Model;

use Generated\Shared\Transfer\PersistentCartShareResourceDataTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;

class ResourceDataMapper
{
    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartShareResourceDataTransfer
     */
    public function mapResourceDataToResourceDataTransfer(ResourceShareTransfer $resourceShareTransfer): PersistentCartShareResourceDataTransfer
    {
        return (new PersistentCartShareResourceDataTransfer())
            ->fromArray((array)json_decode($resourceShareTransfer->getResourceData(), true));
    }
}
