<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Finder\Zed\Business;

use Generated\Shared\Transfer\ClassInformationCollectionTransfer;
use Generated\Shared\Transfer\ModuleTransfer;

interface BusinessModelFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ClassInformationCollectionTransfer
     */
    public function findBusinessModels(ModuleTransfer $moduleTransfer): ClassInformationCollectionTransfer;
}
