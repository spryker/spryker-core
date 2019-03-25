<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\ProductReviewGui\Communication\ProductReviewGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductReviewGui\Persistence\ProductReviewGuiQueryContainerInterface getQueryContainer()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $productReviewTable = $this
            ->getFactory()
            ->createProductReviewTable($this->getCurrentLocale());

        return $this->viewResponse([
            'productReviewTable' => $productReviewTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $productTable = $this
            ->getFactory()
            ->createProductReviewTable($this->getCurrentLocale());

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale()
    {
        return $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();
    }
}
