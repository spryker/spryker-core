<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\NavigationGui\Communication\NavigationGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainerInterface getQueryContainer()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $navigationTable = $this->getFactory()->createNavigationTable();

        return $this->viewResponse([
            'navigationTable' => $navigationTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $navigationTable = $this->getFactory()->createNavigationTable();

        return $this->jsonResponse(
            $navigationTable->fetchData()
        );
    }
}
