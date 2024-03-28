<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Request;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;

class DynamicEntityCollectionRequestTreeBranch implements DynamicEntityCollectionRequestTreeBranchInterface
{
    /**
     * @var \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer
     */
    protected DynamicEntityCollectionRequestTransfer $parentCollectionRequestTransfer;

    /**
     * @var array<\Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer>
     */
    protected array $childCollectionRequestTransfers = [];

    /**
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer
     */
    public function getParentCollectionRequestTransfer(): DynamicEntityCollectionRequestTransfer
    {
        return $this->parentCollectionRequestTransfer;
    }

    /**
     * @return array<\Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer>
     */
    public function getChildCollectionRequestTransfers(): array
    {
        return $this->childCollectionRequestTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Spryker\Zed\DynamicEntity\Business\Request\DynamicEntityCollectionRequestTreeBranchInterface
     */
    public function setParentCollectionRequestTransfer(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCollectionRequestTreeBranchInterface {
        $this->parentCollectionRequestTransfer = $dynamicEntityCollectionRequestTransfer;

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Spryker\Zed\DynamicEntity\Business\Request\DynamicEntityCollectionRequestTreeBranchInterface
     */
    public function addChildCollectionRequestTransfer(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCollectionRequestTreeBranchInterface {
        $this->childCollectionRequestTransfers[] = $dynamicEntityCollectionRequestTransfer;

        return $this;
    }
}
