<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Business\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;

interface ConfigurableBundleReaderInterface
{
    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer[]
     */
    public function getConfigurableBundleTemplates(array $configurableBundleTemplateIds): array;

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[]
     */
    public function getConfigurableBundleTemplateSlots(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer): array;
}
