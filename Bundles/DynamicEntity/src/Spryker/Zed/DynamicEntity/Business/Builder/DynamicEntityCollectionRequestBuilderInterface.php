<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Builder;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;

interface DynamicEntityCollectionRequestBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return array<string>
     */
    public function buildRelationChainsFromDynamicEntityCollectionRequest(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return array<\Spryker\Zed\DynamicEntity\Business\Request\DynamicEntityCollectionRequestTreeBranchInterface>
     */
    public function buildDynamicEntityCollectionRequestTreeBranches(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
    ): array;
}
