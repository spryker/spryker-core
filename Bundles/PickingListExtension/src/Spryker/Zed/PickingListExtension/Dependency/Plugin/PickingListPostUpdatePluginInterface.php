<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;

/**
 * Provides extension capabilities for executing business logic code after a picking list is updated.
 */
interface PickingListPostUpdatePluginInterface
{
    /**
     * Specification:
     * - Executes after a picking list is updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function postUpdate(PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer): PickingListCollectionResponseTransfer;
}
