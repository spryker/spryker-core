<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Option\Input;

use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\SprykGui\Business\Option\OptionBuilderInterface;

class InputOptionBuilder implements OptionBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    public function build(ModuleTransfer $moduleTransfer): ModuleTransfer
    {
        return $moduleTransfer;
    }
}
