<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator;

interface ConfigurableBundleTemplateSlotTranslatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer[] $configurableBundleTemplateSlotStorageTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer[]
     */
    public function translateConfigurableBundleTemplateSlotNames(
        array $configurableBundleTemplateSlotStorageTransfers,
        string $localeName
    ): array;
}
