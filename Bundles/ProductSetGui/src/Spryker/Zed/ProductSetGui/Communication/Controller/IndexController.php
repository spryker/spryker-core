<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $currentLocaleTransfer = $this->getFactory()->getLocaleFacade()->getCurrentLocale();
        $productSetTable = $this
            ->getFactory()
            ->createProductSetTable($currentLocaleTransfer);

        return $this->viewResponse([
            'productSetTable' => $productSetTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $currentLocaleTransfer = $this->getFactory()->getLocaleFacade()->getCurrentLocale();
        $productTable = $this
            ->getFactory()
            ->createProductSetTable($currentLocaleTransfer);

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }
}
