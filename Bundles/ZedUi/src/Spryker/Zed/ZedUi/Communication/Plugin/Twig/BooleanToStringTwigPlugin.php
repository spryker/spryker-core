<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedUi\Communication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\Error\RuntimeError;
use Twig\TwigFilter;

/**
 * @method \Spryker\Zed\ZedUi\Communication\ZedUiCommunicationFactory getFactory()
 */
class BooleanToStringTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const FILTER_NAME_BOOL_TO_STR = 'boolToStr';

    /**
     * {@inheritDoc}
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
        $twig = $this->addTwigFilters($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigFilters(Environment $twig): Environment
    {
        $twig->addFilter($this->getBoolToStrFilter());

        return $twig;
    }

    /**
     * @return \Twig\TwigFilter
     */
    protected function getBoolToStrFilter(): TwigFilter
    {
        return new TwigFilter(static::FILTER_NAME_BOOL_TO_STR, function ($value) {
            if (!is_bool($value)) {
                throw new RuntimeError(sprintf(
                    'The "%s" filter expects boolean, got "%s".',
                    static::FILTER_NAME_BOOL_TO_STR,
                    is_object($value) ? get_class($value) : gettype($value)
                ));
            }

            return $value ? 'true' : 'false';
        });
    }
}
