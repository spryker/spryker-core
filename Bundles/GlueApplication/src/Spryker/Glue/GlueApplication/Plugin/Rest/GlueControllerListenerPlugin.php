<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\Rest;

use Spryker\Glue\GlueApplication\Controller\AbstractRestController;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class GlueControllerListenerPlugin extends AbstractPlugin
{
    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return callable|null
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $currentController = $event->getController();

        [$controller, $action] = $currentController;

        $request = $event->getRequest();
        $apiController = function () use ($controller, $action, $request) {
            return $this->filter($controller, $action, $request);
        };

        $event->setController($apiController);

        return null;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Controller\AbstractRestController $controller
     * @param string $action
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filter(AbstractRestController $controller, string $action, Request $request): Response
    {
        return $this->getFactory()->createControllerFilter()->filter($controller, $action, $request);
    }
}
