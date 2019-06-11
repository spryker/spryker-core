<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\Plugin\SetupFrontend;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SetupFrontendExtension\Dependency\Plugin\YvesFrontendStoreConfigExpanderPluginInterface;

/**
 * @method \Spryker\Zed\Twig\Communication\TwigCommunicationFactory getFactory()
 * @method \Spryker\Zed\Twig\TwigConfig getConfig()
 * @method \Spryker\Zed\Twig\Business\TwigFacadeInterface getFacade()
 */
class ThemeYvesFrontendStoreConfigExpanderPlugin extends AbstractPlugin implements YvesFrontendStoreConfigExpanderPluginInterface
{
    protected const YVES_ASSETS_CONFIG_THEME_KEY = 'currentTheme';
    protected const YVES_ASSETS_CONFIG_DEFAULT_THEME_KEY = 'defaultTheme';

    /**
     * {@inheritdoc}
     * - Expands config data with current and default themes for current store.
     *
     * @api
     *
     * @param array $storeConfigData
     *
     * @return array
     */
    public function expand(array $storeConfigData): array
    {
        $storeConfigData[static::YVES_ASSETS_CONFIG_THEME_KEY] = $this->getConfig()->getYvesThemeName();
        $storeConfigData[static::YVES_ASSETS_CONFIG_DEFAULT_THEME_KEY] = $this->getConfig()->getYvesThemeNameDefault();

        return $storeConfigData;
    }
}
