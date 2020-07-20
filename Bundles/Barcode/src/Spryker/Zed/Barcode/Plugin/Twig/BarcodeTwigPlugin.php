<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Barcode\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\Barcode\Communication\BarcodeCommunicationFactory getFactory()
 */
class BarcodeTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    public const FUNCTION_NAME_BARCODE = 'barcode';

    /**
     * {@inheritDoc}
     * - The plugin displays barcode.
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction($this->getBarcodeFunction());

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function getBarcodeFunction(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_BARCODE, function (string $text, ?string $generatorPlugin = null) {
            $barcodeResponseTransfer = $this->getFactory()
                ->getBarcodeService()
                ->generateBarcode($text, $generatorPlugin);

            return sprintf('<img src="%s,%s">', $barcodeResponseTransfer->getEncoding(), $barcodeResponseTransfer->getCode());
        }, ['is_safe' => ['html']]);
    }
}
