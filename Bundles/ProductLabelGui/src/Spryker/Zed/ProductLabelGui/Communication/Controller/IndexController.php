<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\ProductLabelGui\Communication\ProductLabelGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelGui\Business\ProductLabelGuiFacadeInterface getFacade()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $productLabelTable = $this->getFactory()->createProductLabelTable();

        return $this->viewResponse([
            'productLabelTable' => $productLabelTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $productLabelTable = $this->getFactory()->createProductLabelTable();

        return $this->jsonResponse($productLabelTable->fetchData());
    }
}
