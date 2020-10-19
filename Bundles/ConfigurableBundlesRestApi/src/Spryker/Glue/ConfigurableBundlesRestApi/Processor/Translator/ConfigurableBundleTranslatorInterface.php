<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator;

interface ConfigurableBundleTranslatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[] $configurableBundleTemplateStorageTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[]
     */
    public function translateConfigurableBundleTemplateStorageTransfers(
        array $configurableBundleTemplateStorageTransfers,
        string $localeName
    ): array;
}
