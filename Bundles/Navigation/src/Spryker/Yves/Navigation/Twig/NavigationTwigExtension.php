<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Navigation\Twig;

use Spryker\Client\Navigation\NavigationClientInterface;
use Spryker\Shared\Twig\TwigExtension;
use Spryker\Yves\Kernel\Application;
use Twig\Environment;
use Twig\TwigFunction;

class NavigationTwigExtension extends TwigExtension
{
    public const EXTENSION_NAME = 'NavigationTwigExtension';

    public const FUNCTION_NAME_NAVIGATION = 'spyNavigation';

    /**
     * @var \Spryker\Client\Navigation\NavigationClientInterface
     */
    protected $navigationClient;

    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @var array
     */
    protected static $buffer = [];

    /**
     * @param \Spryker\Client\Navigation\NavigationClientInterface $navigationClient
     * @param \Spryker\Yves\Kernel\Application $application
     */
    public function __construct(NavigationClientInterface $navigationClient, Application $application)
    {
        $this->navigationClient = $navigationClient;
        $this->application = $application;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(self::FUNCTION_NAME_NAVIGATION, [$this, 'renderNavigation'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    /**
     * @param \Twig\Environment $twig
     * @param string $navigationKey
     * @param string $template
     *
     * @return string
     */
    public function renderNavigation(Environment $twig, $navigationKey, $template)
    {
        $key = $navigationKey . '-' . $this->getLocale();

        if (!isset(static::$buffer[$key])) {
            $navigationTreeTransfer = $this->navigationClient->findNavigationTreeByKey($navigationKey, $this->getLocale());

            static::$buffer[$key] = $navigationTreeTransfer;
        }

        $navigationTreeTransfer = static::$buffer[$key];

        if (!$navigationTreeTransfer || !$navigationTreeTransfer->getNavigation()->getIsActive()) {
            return '';
        }

        return $twig->render($template, [
            'navigationTree' => $navigationTreeTransfer,
        ]);
    }

    /**
     * @return string
     */
    protected function getLocale()
    {
        return $this->application['locale'];
    }
}
