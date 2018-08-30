<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Config\Plugin\ServiceProvider;

use ReflectionObject;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Config\ConfigConstants;
use Spryker\Shared\Config\Profiler\ConfigProfilerCollector;
use Spryker\Shared\Config\Profiler\ConfigProfilerCollectorFactory;
use Spryker\Shared\Twig\TwigFilesystemLoader;

/**
 * @deprecated Please use the specific ConfigProfilerServiceProvider for your application.
 *
 * This will be removed with the next major.
 */
class ConfigProfilerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        if ($this->isWebProfilerEnabled($app)) {
            $this->addConfigProfilerCollector($app);
            $this->addCollectorTemplates($app);
            $this->addPathToLoader($app);
        }
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return \Silex\Application
     */
    protected function addConfigProfilerCollector(Application $app)
    {
        $app->extend('data_collectors', function ($collectors) {
            $collectors[ConfigProfilerCollector::SPRYKER_CONFIG_PROFILER] = function () {
                return ConfigProfilerCollectorFactory::createConfigProfilerCollector();
            };

            return $collectors;
        });

        return $app;
    }

    /**
     * @param \Silex\Application $app
     *
     * @return \Silex\Application
     */
    protected function addCollectorTemplates(Application $app)
    {
        $app['data_collector.templates'] = $app->extend('data_collector.templates', function ($templates) {
            $templates[] = [ConfigProfilerCollector::SPRYKER_CONFIG_PROFILER, '@Config/Collector/spryker_config_profiler.html.twig'];

            return $templates;
        });

        return $app;
    }

    /**
     * @param \Silex\Application $app
     *
     * @return bool
     */
    protected function isWebProfilerEnabled(Application $app)
    {
        return (Config::get(ConfigConstants::ENABLE_WEB_PROFILER, false) && $this->loaderExists($app));
    }

    /**
     * @param \Silex\Application $app
     *
     * @return bool
     */
    protected function loaderExists(Application $app)
    {
        return (isset($app[$this->getLoaderKey()]));
    }

    /**
     * @return string
     */
    protected function getLoaderKey()
    {
        return sprintf('twig.loader.%s', strtolower(APPLICATION));
    }

    /**
     * @param \Silex\Application $app
     *
     * @return \Silex\Application
     */
    protected function addPathToLoader(Application $app)
    {
        $loaderKey = $this->getLoaderKey();
        $app[$loaderKey] = $app->extend($loaderKey, function (TwigFilesystemLoader $loader) {
            $pathToTemplates = $this->getPathToTemplates();
            if ($pathToTemplates && method_exists($loader, 'addPath')) {
                $loader->addPath($pathToTemplates);

                return $loader;
            }

            $loaderReflection = new ReflectionObject($loader);
            $pathProperty = $loaderReflection->getProperty('paths');
            $pathProperty->setAccessible(true);
            $paths = $pathProperty->getValue($loader);
            $paths[] = $pathToTemplates;
            $pathProperty->setValue($loader, $paths);

            return $loader;
        });

        return $app;
    }

    /**
     * @return string|null
     */
    protected function getPathToTemplates()
    {
        return realpath(dirname(__DIR__) . '/../Theme/default') ?: null;
    }
}
