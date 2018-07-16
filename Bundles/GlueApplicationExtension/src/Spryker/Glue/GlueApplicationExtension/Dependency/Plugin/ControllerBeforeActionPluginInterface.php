<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ControllerBeforeActionPluginInterface
{
    /**
     * @api
     *
     * Specification:
     *  - Called before calling controller action
     *
     * @param string $action
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function beforeAction(string $action, RestRequestInterface $restRequest): void;
}
