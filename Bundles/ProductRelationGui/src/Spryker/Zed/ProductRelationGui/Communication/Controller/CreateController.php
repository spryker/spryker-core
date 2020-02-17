<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateController extends BaseProductRelationController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $productRelationFormTypeDataProvider = $this->getFactory()
            ->createProductRelationFormTypeDataProvider();

        $productRelationForm = $this->getFactory()
            ->createRelationForm($productRelationFormTypeDataProvider);

        $productRelationTabs = $this->getFactory()
            ->createProductRelationTabs();

        $productRelationForm->handleRequest($request);

        if ($productRelationForm->isSubmitted() && $productRelationForm->isValid()) {
            $idProductRelation = $this->getFactory()
                ->getProductRelationFacade()
                ->createProductRelation($productRelationForm->getData());

            $this->addSuccessMessage('Product relation successfully created');

            $editProductRelationUrl = Url::generate(
                '/product-relation-gui/edit/',
                [
                    EditController::URL_PARAM_ID_PRODUCT_RELATION => $idProductRelation,
                ]
            )->build();

            return $this->redirectResponse($editProductRelationUrl);
        }

        $productTable = $this->getFactory()->createProductTable();
        $productRuleTable = $this->getFactory()->createProductRuleTable(
            $productRelationFormTypeDataProvider->getData()
        );

        return [
            'productRelationTabs' => $productRelationTabs->createView(),
            'productRelationForm' => $productRelationForm->createView(),
            'productTable' => $productTable->render(),
            'productRuleTable' => $productRuleTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $productTable = $this->getFactory()->createProductTable();

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }
}
