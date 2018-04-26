<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Model\PluginClassNameResolver;

interface PluginClassNameResolverInterface
{
    /**
     * @param null|string $generatorPlugin
     *
     * @return string
     */
    public function resolveBarcodeGeneratorPluginClassName(?string $generatorPlugin): string;
}
