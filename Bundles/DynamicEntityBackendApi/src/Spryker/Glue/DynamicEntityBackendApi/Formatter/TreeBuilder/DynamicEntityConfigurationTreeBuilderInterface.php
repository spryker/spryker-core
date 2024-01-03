<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\TreeBuilder;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;

interface DynamicEntityConfigurationTreeBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param int|null $deepLevel
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function buildDynamicEntityConfigurationTransferTree(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        ?int $deepLevel = null
    ): DynamicEntityConfigurationTransfer;
}
