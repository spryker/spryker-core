<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Persistence;

use Generated\Shared\Transfer\ConfigurableBundlePageSearchFilterTransfer;

interface ConfigurableBundlePageSearchRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundlePageSearchFilterTransfer $configurableBundlePageSearchFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundlePageSearchTransfer[]
     */
    public function getConfigurableBundlePageSearchCollection(ConfigurableBundlePageSearchFilterTransfer $configurableBundlePageSearchFilterTransfer): array;
}
