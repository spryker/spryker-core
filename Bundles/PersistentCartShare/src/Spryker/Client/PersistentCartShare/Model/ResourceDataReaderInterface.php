<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\Model;

use Generated\Shared\Transfer\PersistentCartShareResourceDataTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;

interface ResourceDataReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartShareResourceDataTransfer
     */
    public function getResourceDataFromResourceShareTransfer(ResourceShareTransfer $resourceShareTransfer): PersistentCartShareResourceDataTransfer;
}
