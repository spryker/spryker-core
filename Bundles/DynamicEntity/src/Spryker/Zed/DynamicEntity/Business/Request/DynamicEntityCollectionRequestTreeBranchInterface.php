<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Request;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;

interface DynamicEntityCollectionRequestTreeBranchInterface
{
    /**
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer
     */
    public function getParentCollectionRequestTransfer(): DynamicEntityCollectionRequestTransfer;

    /**
     * @return array<\Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer>
     */
    public function getChildCollectionRequestTransfers(): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return self
     */
    public function setParentCollectionRequestTransfer(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): self;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return self
     */
    public function addChildCollectionRequestTransfer(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): self;
}
