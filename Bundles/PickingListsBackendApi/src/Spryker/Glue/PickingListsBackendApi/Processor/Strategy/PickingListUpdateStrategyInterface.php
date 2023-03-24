<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Strategy;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;

interface PickingListUpdateStrategyInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(GlueRequestTransfer $glueRequestTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function update(GlueRequestTransfer $glueRequestTransfer): PickingListCollectionResponseTransfer;
}
