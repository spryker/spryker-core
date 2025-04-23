<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest;

use Spryker\Glue\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ControllerFilterInterface
{
    /**
     * @param \Spryker\Glue\Kernel\Controller\AbstractController $controller
     * @param string $action
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filter(AbstractController $controller, string $action, Request $httpRequest): Response;
}
