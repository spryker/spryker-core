<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Client\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Client\Communication\ClientControllerListenerInterface;
use SprykerFeature\Zed\ZedRequest\Business\Client\Request;
use SprykerFeature\Zed\ZedRequest\Business\Client\Response;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractClientController;
use SprykerFeature\Zed\Application\Communication\Plugin\TransferObject\TransferServer;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ClientControllerListenerPlugin extends AbstractPlugin implements ClientControllerListenerInterface
{

    /**
     * @param FilterControllerEvent $event
     *
     * @return callable
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $currentController = $event->getController();
        $controller = $currentController[0];
        $action = $currentController[1];

        if (!($controller instanceof AbstractClientController)) {
            return $currentController;
        }

        $newController = function() use ($controller, $action) {
            $requestTransfer = $this->getRequestTransfer($controller, $action);
            $result = $controller->$action($requestTransfer->getTransfer(), $requestTransfer);
            $response = $this->getResponse($controller, $result);

            return TransferServer::getInstance()
                ->setResponse($response)
                ->send();
        };

        $event->setController($newController);
    }

    /**
     * @param AbstractClientController $controller
     * @param string $action
     *
     * @return Request
     * @throw \LogicException
     */
    private function getRequestTransfer(AbstractClientController $controller, $action)
    {
        $classRef = new \ReflectionObject($controller);
        $methodRef = $classRef->getMethod($action);
        $parameters = $methodRef->getParameters();
        $countParameters = count($parameters);

        if ($countParameters > 2 || $countParameters === 2 && end($parameters)->getClass() !== 'SprykerFeature\\Shared\\Library\\Transfer\\Request') {
            throw new \LogicException('Only one transfer object can be received in yves-action');
        }

        /* @var $parameter \ReflectionParameter */
        $parameter = array_shift($parameters);
        if ($parameter) {
            $class = $parameter->getClass();
            if (empty($class)) {
                throw new \LogicException('You need to specify a class for the parameter in the yves-action.');
            }

            $this->isSharedTransferClass($class);
        }

        return TransferServer::getInstance()->getRequest();
    }

    /**
     * @param AbstractClientController $controller
     * @param $result
     *
     * @return Response
     */
    private function getResponse(AbstractClientController $controller, $result)
    {
        $response = new Response(Locator::getInstance());

        if ($result instanceof TransferInterface) {
            $response->setTransfer($result);
        }

        $response->addMessages($controller->getMessages());
        $response->addErrorMessages($controller->getErrorMessages());
        $response->setSuccess($controller->getSuccess());

        return $response;
    }

    /**
     * @param \ReflectionClass $class
     *
     * @throw \LogicException
     */
    private function isSharedTransferClass(\ReflectionClass $class)
    {
        $namespaceParts = explode('\\', $class->getNamespaceName());

        if ($namespaceParts[0] !== 'Generated' || $namespaceParts[1] !== 'Shared') {
            throw new \LogicException('Only transfer classes are allowed in yves action as parameter');
        }
    }
}
