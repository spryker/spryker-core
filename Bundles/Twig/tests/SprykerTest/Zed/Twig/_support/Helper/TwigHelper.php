<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin;
use Spryker\Zed\Twig\Communication\Plugin\EventDispatcher\TwigEventDispatcherPlugin;
use Spryker\Zed\Twig\Communication\Plugin\FilesystemTwigLoaderPlugin;
use Spryker\Zed\Twig\Communication\TwigCommunicationFactory;
use Spryker\Zed\Twig\TwigConfig;
use Spryker\Zed\Twig\TwigDependencyProvider;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Zed\Application\Helper\ApplicationHelperTrait;
use SprykerTest\Zed\EventDispatcher\Helper\EventDispatcherHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use SprykerTest\Zed\Testify\Helper\Communication\CommunicationHelperTrait;
use SprykerTest\Zed\Testify\Helper\Communication\DependencyProviderHelperTrait;

class TwigHelper extends Module
{
    use ApplicationHelperTrait;
    use CommunicationHelperTrait;
    use BusinessHelperTrait;
    use ConfigHelperTrait;
    use DependencyProviderHelperTrait;
    use EventDispatcherHelperTrait;

    protected const MODULE_NAME = 'Twig';
    protected const CONFIG_KEY_TWIG_PLUGINS = 'twigPlugins';
    protected const CONFIG_KEY_LOADER_PLUGINS = 'loaderPlugins';

    /**
     * @var \Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface[]
     */
    protected $twigPlugins = [];

    /**
     * @var \Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface[]
     */
    protected $loaderPlugins = [];

    /**
     * @var array
     */
    protected $config = [
        self::CONFIG_KEY_TWIG_PLUGINS => [],
        self::CONFIG_KEY_LOADER_PLUGINS => [],
    ];

    /**
     * @var string[]
     */
    protected $defaultLoaderPlugins = [
        FilesystemTwigLoaderPlugin::class,
    ];

    /**
     * @return void
     */
    public function _initialize(): void
    {
        foreach ($this->config[static::CONFIG_KEY_TWIG_PLUGINS] as $twigPlugin) {
            $this->twigPlugins[$twigPlugin] = new $twigPlugin();
        }

        foreach ($this->config[static::CONFIG_KEY_LOADER_PLUGINS] as $loaderPlugin) {
            $this->loaderPlugins[$loaderPlugin] = new $loaderPlugin();
        }

        foreach ($this->defaultLoaderPlugins as $defaultLoaderPlugin) {
            if (!isset($this->loaderPlugins[$defaultLoaderPlugin])) {
                $templatePaths = [rtrim(APPLICATION_VENDOR_DIR, '/') . '/spryker/spryker/Bundles/%2$s/src/Spryker/Zed/%1$s/Presentation/'];
                $this->getConfigHelper()->mockConfigMethod('addCoreTemplatePaths', $templatePaths, static::MODULE_NAME);
                $twigFactory = $this->getCommunicationHelper()->getFactory(static::MODULE_NAME);
                $twigLoaderPlugin = new $defaultLoaderPlugin();
                $twigLoaderPlugin->setFactory($twigFactory);
                $this->loaderPlugins[$defaultLoaderPlugin] = $twigLoaderPlugin;
            }
        }
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        $this->getEventDispatcherHelper()->addEventDispatcherPlugin(new TwigEventDispatcherPlugin());

        $this->getApplicationHelper()->addApplicationPlugin(
            $this->getTwigApplicationPluginStub()
        );

        $this->addDependencies();
    }

    /**
     * @return \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin
     */
    protected function getTwigApplicationPluginStub(): TwigApplicationPlugin
    {
        /** @var \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin $twigApplicationPlugin */
        $twigApplicationPlugin = Stub::make(TwigApplicationPlugin::class, [
            'getFactory' => function () {
                return $this->getFactory();
            },
            'getConfig' => function () {
                return $this->getConfig();
            },
        ]);

        return $twigApplicationPlugin;
    }

    /**
     * @return void
     */
    protected function addDependencies(): void
    {
        $this->getDependencyProviderHelper()->setDependency(TwigDependencyProvider::PLUGINS_TWIG, $this->twigPlugins);
        $this->getDependencyProviderHelper()->setDependency(TwigDependencyProvider::PLUGINS_TWIG_LOADER, $this->loaderPlugins);
    }

    /**
     * @return \Spryker\Zed\Twig\Communication\TwigCommunicationFactory
     */
    protected function getFactory(): TwigCommunicationFactory
    {
        /** @var \Spryker\Zed\Twig\Communication\TwigCommunicationFactory $twigCommunicationFactory */
        $twigCommunicationFactory = $this->getCommunicationHelper()->getFactory(static::MODULE_NAME);

        return $twigCommunicationFactory;
    }

    /**
     * @return \Spryker\Zed\Twig\TwigConfig
     */
    protected function getConfig(): TwigConfig
    {
        $this->getConfigHelper()->mockConfigMethod('getTemplatePaths', function () {
            $twigConfig = new TwigConfig();
            $paths = $twigConfig->getTemplatePaths();
            $paths[] = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/%2$s/src/*/Zed/%1$s/Presentation';

            return $paths;
        }, static::MODULE_NAME);

        /** @var \Spryker\Zed\Twig\TwigConfig $twigConfig */
        $twigConfig = $this->getConfigHelper()->getModuleConfig(static::MODULE_NAME);

        return $twigConfig;
    }

    /**
     * @param \Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface $twigPlugin
     *
     * @return $this
     */
    public function addTwigPlugin(TwigPluginInterface $twigPlugin)
    {
        $this->twigPlugins[] = $twigPlugin;

        $this->addDependencies();

        return $this;
    }

    /**
     * @param \Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface $loaderPlugin
     *
     * @return $this
     */
    public function addLoaderPlugin(TwigLoaderPluginInterface $loaderPlugin)
    {
        $this->loaderPlugins[] = $loaderPlugin;

        $this->addDependencies();

        return $this;
    }
}
