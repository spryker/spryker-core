<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Updater;

use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer;

interface DynamicEntityConfigurationUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function updateDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer;
}
