<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\GlueApplication\Backend;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\Exception\ControllerNotFoundException;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Spryker\Glue\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
abstract class AbstractResourcePlugin extends AbstractPlugin implements ResourceInterface
{
    /**
     * {@inheritDoc}
     * - Returns the executable controller/action pair based on the `ResourceInterface::getDeclaredMethods()`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return callable
     */
    public function getResource(GlueRequestTransfer $glueRequestTransfer): callable
    {
        $glueResourceMethodCollectionTransfer = $this->getDeclaredMethods();

        /** @var \Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer|null $glueResourceMethodConfigurationTransfer */
        $glueResourceMethodConfigurationTransfer = $glueResourceMethodCollectionTransfer
            ->offsetGet($glueRequestTransfer->getResource()->getMethod());

        if ($glueResourceMethodConfigurationTransfer) {
            $controller = $glueResourceMethodConfigurationTransfer->getController() ?? $this->getController();

            return [
                $this->createControllerInstance($controller),
                $glueResourceMethodConfigurationTransfer->getAction() ?? $this->getActionName($glueRequestTransfer),
            ];
        }

        $controller = $this->createControllerInstance($this->getController());
        $actionName = $this->getActionName($glueRequestTransfer);

        if (!method_exists($controller, $actionName)) {
            return function (): GlueResponseTransfer {
                $glueErrorTransfer = (new GlueErrorTransfer())
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->setCode(GlueApplicationConfig::ERROR_CODE_METHOD_NOT_FOUND)
                    ->setMessage(GlueApplicationConfig::ERROR_MESSAGE_METHOD_NOT_FOUND);

                return (new GlueResponseTransfer())
                    ->setHttpStatus(Response::HTTP_NOT_FOUND)
                    ->addError($glueErrorTransfer);
            };
        }

        return [
            $controller,
            $actionName,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return string
     */
    protected function getActionName(GlueRequestTransfer $glueRequestTransfer): string
    {
        $actionName = $glueRequestTransfer->getResource()->getMethod();
        if (!preg_match('/Action$/i', $actionName)) {
            $actionName = sprintf('%sAction', $actionName);
        }

        return $actionName;
    }

    /**
     * @param string $controller
     *
     * @throws \Spryker\Glue\GlueApplication\Exception\ControllerNotFoundException
     *
     * @return \Spryker\Glue\Kernel\Controller\AbstractController
     */
    protected function createControllerInstance(string $controller): AbstractController
    {
        if (class_exists($controller)) {
            return new $controller();
        }

        throw new ControllerNotFoundException('Controller not found!');
    }
}
