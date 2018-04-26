<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Model\PluginClassNameResolver;

use Spryker\Service\Barcode\Dependency\Plugin\DummyPlugin;

class PluginClassNameResolver implements PluginClassNameResolverInterface
{
    protected const DEFAULT_BARCODE_GENERATOR_PLUGIN_FQCN = DummyPlugin::class;

    /**
     * @param null|string $generatorPlugin
     *
     * @return string
     */
    public function resolveBarcodeGeneratorPluginClassName(?string $generatorPlugin): string
    {
        return ($generatorPlugin === null)
            ? static::DEFAULT_BARCODE_GENERATOR_PLUGIN_FQCN
            : $generatorPlugin;
    }
}
