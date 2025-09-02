<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 */
class ListModelController extends AbstractGatewayController
{
    /**
     * @uses SprykerFeature\Zed\SelfServicePortal\Communication\Controller\AddModelController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_SSP_ADD_MODEL = '/self-service-portal/add-model';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function indexAction(Request $request): array
    {
        $sspModelTable = $this->getFactory()->createSspModelTable();

        return $this->viewResponse([
            'sspModelTable' => $sspModelTable->render(),
            'addModelRoute' => static::ROUTE_SSP_ADD_MODEL,
        ]);
    }

    public function tableAction(Request $request): JsonResponse
    {
        $sspModelTable = $this->getFactory()->createSspModelTable();

        return $this->jsonResponse(
            $sspModelTable->fetchData(),
        );
    }
}
