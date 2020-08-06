<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplateImageStorageTransfer;

interface ConfigurableBundleTemplateImageStorageReaderInterface
{
    /**
     * @param int $idConfigurableBundleTemplate
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateImageStorageTransfer|null
     */
    public function findConfigurableBundleTemplateImageStorage(
        int $idConfigurableBundleTemplate,
        string $localeName
    ): ?ConfigurableBundleTemplateImageStorageTransfer;
}
