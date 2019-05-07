<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Config\Communication\Plugin\WebProfiler;

use Exception;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WebProfilerExtension\Dependency\Plugin\WebProfilerDataCollectorPluginInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

/**
 * @method \Spryker\Zed\Config\Business\ConfigFacadeInterface getFacade()
 * @method \Spryker\Zed\Config\ConfigConfig getConfig()
 */
class WebProfilerConfigDataCollectorPlugin extends AbstractPlugin implements WebProfilerDataCollectorPluginInterface, DataCollectorInterface
{
    public const SPRYKER_CONFIG_PROFILER = 'spryker_config_profiler';

    /**
     * @var array|null
     */
    protected $data;

    /**
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::SPRYKER_CONFIG_PROFILER;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTemplateName(): string
    {
        return '@Config/Collector/spryker_config_profiler.html.twig';
    }

    /**
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface
     */
    public function getDataCollector(ContainerInterface $container): DataCollectorInterface
    {
        return $this;
    }

    /**
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Exception|null $exception
     *
     * @return array
     */
    public function collect(Request $request, Response $response, ?Exception $exception = null)
    {
        $this->data = $this->getFacade()->getProfileData();

        return $this->data;
    }

    /**
     * @api
     *
     * @return array
     */
    public function getConfigs(): array
    {
        return $this->data;
    }

    /**
     * @api
     *
     * @return void
     */
    public function reset(): void
    {
        $this->data = null;
    }
}
