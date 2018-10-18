<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\Rest\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Glue\GlueApplication\Exception\GlueApplicationBootstrapException;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\Kernel\Application as GlueApplication;
use Spryker\Glue\Kernel\ControllerResolver\GlueFragmentControllerResolver;
use Spryker\Glue\Kernel\Plugin\Pimple;
use Spryker\Shared\Config\Config;
use Spryker\Shared\GlueApplication\GlueApplicationConstants;

class GlueApplicationServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @var \Silex\Application
     */
    private $application;

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $this->application = $app;

        $this->setPimpleApplication();
        $this->setDebugMode();
        $this->setControllerResolver();
    }

    /**
     * @throws \Spryker\Glue\GlueApplication\Exception\GlueApplicationBootstrapException
     *
     * @return void
     */
    protected function setPimpleApplication()
    {
        if (!($this->application instanceof GlueApplication)) {
            throw new GlueApplicationBootstrapException(
                sprintf("Silex application must extend Glue application '%s'.", GlueApplication::class)
            );
        }

        $pimplePlugin = new Pimple();
        $pimplePlugin->setApplication($this->application);
    }

    /**
     * @return void
     */
    protected function setControllerResolver()
    {
        $this->application['resolver'] = $this->application->share(function () {
            return new GlueFragmentControllerResolver($this->application);
        });
    }

    /**
     * @return void
     */
    protected function setDebugMode()
    {
        $this->application['debug'] = Config::get(GlueApplicationConstants::GLUE_APPLICATION_REST_DEBUG, false);
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
