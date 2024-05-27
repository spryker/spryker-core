<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Deleter;

use Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;

interface DynamicEntityDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer $dynamicEntityCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function deleteEntityCollection(
        DynamicEntityCollectionDeleteCriteriaTransfer $dynamicEntityCollectionDeleteCriteriaTransfer
    ): DynamicEntityCollectionResponseTransfer;
}
