<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\Setter;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

interface ConfigurationDefaultValuesSetterInterface
{
    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function setDefaultValues(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer;
}
