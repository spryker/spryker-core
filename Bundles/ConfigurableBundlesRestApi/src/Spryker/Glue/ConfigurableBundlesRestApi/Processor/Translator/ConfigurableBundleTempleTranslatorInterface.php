<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator;

interface ConfigurableBundleTempleTranslatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestConfigurableBundleTemplatesAttributesTransfer[] $restConfigurableBundleTemplatesAttributesTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestConfigurableBundleTemplatesAttributesTransfer[]
     */
    public function translateConfigurableBundleTemplateNames(
        array $restConfigurableBundleTemplatesAttributesTransfers,
        string $localeName
    ): array;
}
