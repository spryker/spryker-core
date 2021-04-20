<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ReturnCollectionTransfer;

/**
 * @deprecated Will be removed without replacement.
 *
 * Allows to expand ReturnCollectionTransfer with additional data.
 */
interface ReturnCollectionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands ReturnCollectionTransfer transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnCollectionTransfer $returnCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function expand(ReturnCollectionTransfer $returnCollectionTransfer): ReturnCollectionTransfer;
}
