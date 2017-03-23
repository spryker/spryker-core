<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductRelation\Communication\ProductRelationCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface getQueryContainer()
 */
class EditController extends BaseProductRelationController
{

    const URL_PARAM_ID_PRODUCT_RELATION = 'id-product-relation';
    const URL_PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductRelation = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_RELATION));

        $productRelationFormTypeDataProvider = $this->getFactory()
            ->createProductRelationFormTypeDataProvider();

        $productRelationForm = $this->getFactory()
            ->createRelationForm($productRelationFormTypeDataProvider, $idProductRelation);

        $productRelationTabs = $this->getFactory()
            ->createProductRelationTabs();

        $productTable = $this->getFactory()->createProductTable($idProductRelation);

        $productRelationForm->handleRequest($request);

        if ($productRelationForm->isValid()) {
            $this->getFacade()->updateProductRelation($productRelationForm->getData());

            $this->addSuccessMessage('Product relation successfully modified');

            $editProductRelationUrl = Url::generate(
                '/product-relation/edit/',
                [
                    EditController::URL_PARAM_ID_PRODUCT_RELATION => $idProductRelation,
                ]
            )->build();

            return $this->redirectResponse($editProductRelationUrl);
        }

        $productRelationTransfer = $this->getFacade()
            ->findProductRelationById($idProductRelation);

        $productRuleTable = $this->getFactory()
            ->createProductRuleTable($productRelationTransfer);

        return [
            'productRelationTabs' => $productRelationTabs->createView(),
            'productRelationForm' => $productRelationForm->createView(),
            'productTable' => $productTable->render(),
            'productRelation' => $productRelationTransfer,
            'productRuleTable' => $productRuleTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $productTable = $this->getFactory()
            ->createProductTable();

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request)
    {
        $idProductRelation = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_RELATION));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);

        $this->getFacade()
            ->activateProductRelation($idProductRelation);

        $this->addSuccessMessage('Relation successfully activated.');

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request)
    {
        $idProductRelation = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_RELATION));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);

        $this->getFacade()
            ->deactivateProductRelation($idProductRelation);

        $this->addSuccessMessage('Relation successfully deactivated.');

        return $this->redirectResponse($redirectUrl);
    }

}
