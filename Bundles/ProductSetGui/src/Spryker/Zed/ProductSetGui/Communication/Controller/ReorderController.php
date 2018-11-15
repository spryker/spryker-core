<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 */
class ReorderController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createReorderProductSetsFormDataProvider();
        $reorderProductSetsForm = $this->getFactory()
            ->getReorderProductSetsForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($reorderProductSetsForm->isSubmitted() && $reorderProductSetsForm->isValid()) {
            $productSetTransfers = $this->getFactory()
                ->createReorderFormDataToTransferMapper()
                ->mapData($reorderProductSetsForm);

            $this->getFactory()
                ->getProductSetFacade()
                ->reorderProductSets($productSetTransfers);

            $this->addSuccessMessage('Product Sets reordered successfully.');

            return $this->redirectResponse(Url::generate('/product-set-gui')->build());
        }

        $currentLocaleTransfer = $this->getFactory()->getLocaleFacade()->getCurrentLocale();
        $productSetTable = $this
            ->getFactory()
            ->createProductSetReorderTable($currentLocaleTransfer);

        return $this->viewResponse([
            'productSetReorderTable' => $productSetTable->render(),
            'reorderProductSetsForm' => $reorderProductSetsForm->createView(),
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
            ->createProductSetReorderTable($currentLocaleTransfer);

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }
}
