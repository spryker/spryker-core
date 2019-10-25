<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Persistence;

use Generated\Shared\Transfer\ConfigurableBundlePageSearchTransfer;

interface ConfigurableBundlePageSearchEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundlePageSearchTransfer $configurableBundlePageSearchTransfer
     *
     * @return void
     */
    public function createConfigurableBundlePageSearch(ConfigurableBundlePageSearchTransfer $configurableBundlePageSearchTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundlePageSearchTransfer $configurableBundlePageSearchTransfer
     *
     * @return void
     */
    public function updateConfigurableBundlePageSearch(ConfigurableBundlePageSearchTransfer $configurableBundlePageSearchTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundlePageSearchTransfer $configurableBundlePageSearchTransfer
     *
     * @return void
     */
    public function deleteConfigurableBundlePageSearch(ConfigurableBundlePageSearchTransfer $configurableBundlePageSearchTransfer): void;
}
