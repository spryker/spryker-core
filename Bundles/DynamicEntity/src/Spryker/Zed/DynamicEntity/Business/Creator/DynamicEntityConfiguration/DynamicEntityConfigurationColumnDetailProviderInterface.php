<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityConfiguration;

use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;

/**
 * Provide column details for all DynamicEntityConfigurationTransfer's.
 */
interface DynamicEntityConfigurationColumnDetailProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function provideColumDetails(
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityConfigurationCollectionTransfer;
}
