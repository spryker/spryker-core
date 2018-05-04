<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Model\BarcodeGenerator;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\Barcode\Model\BarcodeGeneratorPluginResolver\BarcodeGeneratorPluginResolverInterface;

class BarcodeGenerator implements BarcodeGeneratorInterface
{
    /**
     * @var \Spryker\Service\Barcode\Model\BarcodeGeneratorPluginResolver\BarcodeGeneratorPluginResolverInterface
     */
    protected $pluginResolver;

    /**
     * @param \Spryker\Service\Barcode\Model\BarcodeGeneratorPluginResolver\BarcodeGeneratorPluginResolverInterface $pluginResolver
     */
    public function __construct(BarcodeGeneratorPluginResolverInterface $pluginResolver)
    {
        $this->pluginResolver = $pluginResolver;
    }

    /**
     * @param string $text
     * @param null|string $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(string $text, ?string $generatorPlugin): BarcodeResponseTransfer
    {
        return $this->pluginResolver
            ->getBarcodeGeneratorPlugin($generatorPlugin)
            ->generate($text);
    }
}
