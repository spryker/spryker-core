<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage\Expander;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;

interface ConfigurableBundleTemplateImageStorageExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer
     */
    public function expandConfigurableBundleTemplateStorageWithImageSets(
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer,
        string $localeName
    ): ConfigurableBundleTemplateStorageTransfer;

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[] $configurableBundleTemplateStorageTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[]
     */
    public function expandConfigurableBundleTemplatesStorageWithImageSets(
        array $configurableBundleTemplateStorageTransfers,
        string $localeName
    ): array;
}
