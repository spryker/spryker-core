<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest;

use Spryker\Glue\GlueApplication\Controller\AbstractRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ControllerFilterInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Controller\AbstractRestController $controller
     * @param string $action
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filter(AbstractRestController $controller, string $action, Request $httpRequest): Response;
}
