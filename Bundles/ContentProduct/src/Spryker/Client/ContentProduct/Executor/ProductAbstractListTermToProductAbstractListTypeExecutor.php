<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Executor;

use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;

class ProductAbstractListTermToProductAbstractListTypeExecutor implements ProductAbstractListTermToProductAbstractListTypeExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTermTransfer $contentProductAbstractListTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer
     */
    public function execute(ContentProductAbstractListTermTransfer $contentProductAbstractListTermTransfer): ContentProductAbstractListTypeTransfer
    {
        $contentProductAbstractListTypeTransfer = new ContentProductAbstractListTypeTransfer();
        $contentProductAbstractListTypeTransfer->setIdProductAbstracts($contentProductAbstractListTermTransfer->getIdProductAbstracts());

        return $contentProductAbstractListTypeTransfer;
    }
}
