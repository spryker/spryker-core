<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ErrorHandler\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Whoops\Handler\CallbackHandler;
use Whoops\Handler\HandlerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * @method \Spryker\Zed\ErrorHandler\ErrorHandlerConfig getConfig()
 */
class ErrorHandlerApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * {@inheritDoc}
     * - Register the Whoops error handler which provides a pretty error interface when its enabled.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        if ($this->getConfig()->isPrettyErrorHandlerEnabled()) {
            $this->registerPrettyErrorHandler();
        }

        return $container;
    }

    /**
     * @return void
     */
    protected function registerPrettyErrorHandler(): void
    {
        $whoops = new Run();
        $whoops->appendHandler($this->getPrettyPageHandler());
        $whoops->appendHandler($this->getErrorLoggerCallbackHandler());

        $whoops->register();
    }

    /**
     * @return \Whoops\Handler\HandlerInterface
     */
    protected function getPrettyPageHandler(): HandlerInterface
    {
        $userPath = $this->getConfig()->getUserBasePath();
        $handler = new PrettyPageHandler();
        if ($userPath) {
            $handler->setEditor(function ($file, $line) use ($userPath) {
                $serverPath = $this->getConfig()->getServerBasePath();
                $file = str_replace($serverPath, $userPath, $file);

                $pattern = $this->getConfig()->getIdeLink();
                $url = sprintf($pattern, $file, $line);

                if (!$this->getConfig()->isAjaxRequiredByIde()) {
                    return $url;
                }

                return [
                    'url' => $url,
                    'ajax' => true,
                ];
            });
        }

        return $handler;
    }

    /**
     * @return \Whoops\Handler\HandlerInterface
     */
    protected function getErrorLoggerCallbackHandler(): HandlerInterface
    {
        return new CallbackHandler(function ($exception) {
            ErrorLogger::getInstance()->log($exception);
        });
    }
}
