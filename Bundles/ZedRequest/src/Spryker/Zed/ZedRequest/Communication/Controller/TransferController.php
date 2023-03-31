<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Communication\Controller;

use ReflectionProperty;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ZedRequest\Communication\Exception\NotAllowedActionException;
use Spryker\Zed\ZedRequest\Communication\Plugin\TransferObject\TransferServer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @method \Spryker\Zed\ZedRequest\Business\ZedRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\ZedRequest\Communication\ZedRequestCommunicationFactory getFactory()
 */
class TransferController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Zed\ZedRequest\Communication\Exception\NotAllowedActionException
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     */
    public function repeatAction(Request $request)
    {
        if ($this->getFactory()->getConfig()->isRepeatEnabled() === false) {
            throw new NotAllowedActionException('This action is not allowed to execute repeated Zed requests.');
        }

        /** @phpstan-var string|null */
        $mvc = $request->query->get('mvc', null);
        $repeatData = $this->getFacade()->getRepeatData($mvc);

        if (!is_array($repeatData) || !$repeatData) {
            return new Response('No request to repeat.');
        }

        TransferServer::getInstance()->activateRepeating();
        $request = Request::createFromGlobals();
        $request->attributes->set('module', $repeatData['module']);
        $request->attributes->set('controller', $repeatData['controller']);
        $request->attributes->set('action', $repeatData['action']);

        $reflectionProperty = new ReflectionProperty($request, 'pathInfo');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($request, sprintf('/%s/%s/%s', $repeatData['module'], $repeatData['controller'], $repeatData['action']));

        $request->request->replace($repeatData);

        return $this->handle($request, HttpKernelInterface::SUB_REQUEST);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handle(Request $request, int $type): Response
    {
        $current = $this->getApplication()->get('request');
        $this->getApplication()->set('request', $request);

        $response = $this->getKernel()->handle($request, $type);
        $this->getApplication()->set('request', $current);

        return $response;
    }

    /**
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected function getKernel(): HttpKernelInterface
    {
        return $this->getApplication()->get('kernel');
    }
}
