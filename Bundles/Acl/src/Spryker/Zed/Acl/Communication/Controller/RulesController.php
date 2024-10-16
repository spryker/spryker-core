<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Acl\Persistence\AclRepositoryInterface getRepository()
 */
class RulesController extends AbstractController
{
    /**
     * @var string
     */
    protected const BUNDLE_FIELD = 'bundle';

    /**
     * @var string
     */
    protected const CONTROLLER_FIELD = 'controller';

    /**
     * @var string
     */
    protected const ROOT_ACCESS = '*';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function controllerChoicesAction(Request $request): JsonResponse
    {
        $bundle = (string)$request->query->get(static::BUNDLE_FIELD);
        if ($bundle === static::ROOT_ACCESS) {
            return $this->jsonResponse([static::ROOT_ACCESS => static::ROOT_ACCESS]);
        }

        $choices = $this->getFactory()->getRouterFacade()->getRouterControllerCollection($bundle)->getControllers();

        array_unshift($choices, static::ROOT_ACCESS);

        return $this->jsonResponse(array_combine($choices, $choices));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function actionChoicesAction(Request $request): JsonResponse
    {
        $bundle = (string)$request->query->get(static::BUNDLE_FIELD);
        $controller = (string)$request->query->get(static::CONTROLLER_FIELD);

        if ($bundle === static::ROOT_ACCESS && $controller !== static::ROOT_ACCESS) {
            return $this->jsonResponse(['error' => 'Please select a bundle first.'], 400);
        }

        if ($controller === static::ROOT_ACCESS) {
            return $this->jsonResponse([static::ROOT_ACCESS => static::ROOT_ACCESS]);
        }

        $choices = $this->getFactory()
            ->getRouterFacade()
            ->getRouterActionCollection($bundle, $controller)
            ->getActions();

        array_unshift($choices, static::ROOT_ACCESS);

        return $this->jsonResponse(array_combine($choices, $choices));
    }
}
