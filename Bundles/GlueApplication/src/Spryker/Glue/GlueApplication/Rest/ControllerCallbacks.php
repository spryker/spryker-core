<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ControllerCallbacks implements ControllerCallbacksInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerBeforeActionPluginInterface[]
     */
    protected $controllerBeforeActionPlugins = [];

    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerAfterActionPluginInterface[]
     */
    protected $controllerAfterActionPlugins = [];

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerBeforeActionPluginInterface[] $controllerBeforeActionPlugins
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerAfterActionPluginInterface[] $controllerAfterActionPlugins
     */
    public function __construct(array $controllerBeforeActionPlugins, array $controllerAfterActionPlugins)
    {
        $this->controllerBeforeActionPlugins = $controllerBeforeActionPlugins;
        $this->controllerAfterActionPlugins = $controllerAfterActionPlugins;
    }

    /**
     * @param string $action
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function beforeAction(string $action, RestRequestInterface $restRequest): void
    {
        foreach ($this->controllerBeforeActionPlugins as $controllerBeforeActionPlugin) {
            $controllerBeforeActionPlugin->beforeAction($action, $restRequest);
        }
    }

    /**
     * @param string $action
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return void
     */
    public function afterAction(
        string $action,
        RestRequestInterface $restRequest,
        RestResponseInterface $restResponse
    ): void {
        foreach ($this->controllerAfterActionPlugins as $controllerAfterActionPlugin) {
            $controllerAfterActionPlugin->afterAction($action, $restRequest, $restResponse);
        }
    }
}
