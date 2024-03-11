<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;

interface DynamicEntityPostEditRequestExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityTransfer> $dynamicEntityTransfers
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer> $dynamicEntityPostEditRequestTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer>
     */
    public function expandDynamicEntityCollectionResponseTransferWithRawDynamicEntityTransfers(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        ArrayObject $dynamicEntityTransfers,
        array $dynamicEntityPostEditRequestTransfers
    ): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $parentDynamicEntityConfigurationTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityRelationTransfer> $childRelationTransfers
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer> $dynamicEntityPostEditRequestTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer>
     */
    public function expandDynamicEntityCollectionResponseTransferWithChildRawDynamicEntityTransfers(
        DynamicEntityConfigurationTransfer $parentDynamicEntityConfigurationTransfer,
        ArrayObject $childRelationTransfers,
        array $dynamicEntityPostEditRequestTransfers
    ): array;
}
