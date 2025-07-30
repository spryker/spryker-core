<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

interface FileImportHistoryGuiTableConfigurationProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer;
}
