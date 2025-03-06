<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFilter;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Communication\SspFileManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspFileManagement\SspFileManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 */
class FileSizeFormatterTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * @var string
     */
    protected const FILTER_NAME = 'formatFileSize';

    /**
     * @var int
     */
    protected const NUMBER_OF_DECIMALS = 2;

    /**
     * {@inheritDoc}
     * - Formats the file size into a human-readable format.
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
        $twig->addFilter($this->createFilter());

        return $twig;
    }

    /**
     * @return \Twig\TwigFilter
     */
    protected function createFilter(): TwigFilter
    {
        return new TwigFilter(
            static::FILTER_NAME,
            function (int $fileSize, int $numberOfDecimals = self::NUMBER_OF_DECIMALS): string {
                return $this->getFactory()->createFileSizeFormatter()->formatFileSize($fileSize, $numberOfDecimals);
            },
        );
    }
}
