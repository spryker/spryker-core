<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\Translator;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

interface ConfigurationTranslatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function translateConfiguration(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer;
}
