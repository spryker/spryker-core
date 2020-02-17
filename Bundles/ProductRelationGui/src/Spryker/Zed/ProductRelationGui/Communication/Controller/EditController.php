<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class EditController extends BaseProductRelationController
{
    public const URL_PARAM_ID_PRODUCT_RELATION = 'id-product-relation';
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_SUCCESS = 'Product relation successfully modified';
    protected const MESSAGE_ACTIVATE_SUCCESS = 'Relation successfully activated.';
    protected const MESSAGE_DEACTIVATE_SUCCESS = 'Relation successfully deactivated.';

    protected const REDIRECT_URL_EDIT = '/product-relation-gui/edit/';
    protected const REDIRECT_URL_LIST = '/product-relation-gui/list';

    protected const ERROR_MESSAGE = 'Product relation with id "%d" not found.';

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

        $productRelationForm->handleRequest($request);

        if ($productRelationForm->isSubmitted() && $productRelationForm->isValid()) {
            $this->getFactory()
                ->getProductRelationFacade()
                ->updateProductRelation($productRelationForm->getData());

            $this->addSuccessMessage(static::MESSAGE_SUCCESS);

            $editProductRelationUrl = Url::generate(
                static::REDIRECT_URL_EDIT,
                [
                    static::URL_PARAM_ID_PRODUCT_RELATION => $idProductRelation,
                ]
            )->build();

            return $this->redirectResponse($editProductRelationUrl);
        }

        $productRelationTransfer = $this->getFactory()
            ->getProductRelationFacade()
            ->findProductRelationById($idProductRelation);

        if ($productRelationTransfer === null) {
            $this->addErrorMessage(sprintf(
                static::ERROR_MESSAGE,
                $idProductRelation
            ));

            return $this->redirectResponse(static::REDIRECT_URL_LIST);
        }

        $productRuleTable = $this->getFactory()
            ->createProductRuleTable($productRelationTransfer);

        return [
            'productRelationTabs' => $productRelationTabs->createView(),
            'productRelationForm' => $productRelationForm->createView(),
            'productRelation' => $productRelationTransfer,
            'productRuleTable' => $productRuleTable->render(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request): RedirectResponse
    {
        $idProductRelation = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_RELATION));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);

        $this->getFactory()
            ->getProductRelationFacade()
            ->activateProductRelation($idProductRelation);

        $this->addSuccessMessage(static::MESSAGE_ACTIVATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request): RedirectResponse
    {
        $idProductRelation = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_RELATION));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);

        $this->getFactory()
            ->getProductRelationFacade()
            ->deactivateProductRelation($idProductRelation);

        $this->addSuccessMessage(static::MESSAGE_DEACTIVATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }
}
