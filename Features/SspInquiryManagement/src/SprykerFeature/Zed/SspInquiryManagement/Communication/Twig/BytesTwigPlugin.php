<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

class BytesTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * @var string
     */
    public const FUNCTION_NAME_BYTES = 'format_bytes';

    /**
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction($this->formatBytes());

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function formatBytes(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_BYTES, function ($bytes) {
            if ($bytes >= 1000 * 1000) {
                return round($bytes / (1000 * 1000), 2) . ' MB';
            } elseif ($bytes >= 1000) {
                return round($bytes / 1000, 2) . ' kB';
            } else {
                return $bytes . ' B';
            }
        }, ['is_safe' => ['html']]);
    }
}
