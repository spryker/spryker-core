<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Controller;

use Generated\Shared\Transfer\ProductRelationResponseTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateController extends BaseProductRelationController
{
    /**
     * @uses \Spryker\Zed\ProductRelationGui\Communication\Controller\EditController::indexAction()
     */
    protected const REDIRECT_URL_EDIT = '/product-relation-gui/edit/index';

    /**
     * @uses \Spryker\Zed\ProductRelationGui\Communication\Controller\ListController::indexAction()
     */
    protected const REDIRECT_URL_LIST = '/product-relation-gui/list/index';
    protected const MESSAGE_SUCCESS = 'Product relation successfully created';

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
            ->createRelationForm(
                $productRelationFormTypeDataProvider->getData(),
                $productRelationFormTypeDataProvider->getOptions()
            );

        $productRelationTabs = $this->getFactory()
            ->createProductRelationTabs();

        $productRelationForm->handleRequest($request);

        if ($productRelationForm->isSubmitted() && $productRelationForm->isValid()) {
            return $this->handleSubmitForm($productRelationForm);
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
     * @param \Symfony\Component\Form\FormInterface $productRelationForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubmitForm(FormInterface $productRelationForm): RedirectResponse
    {
        $productRelationResponseTransfer = $this->getFactory()
            ->getProductRelationFacade()
            ->createProductRelation($productRelationForm->getData());

        if (!$productRelationResponseTransfer->getIsSuccessful()) {
            $this->processErrorMessages($productRelationResponseTransfer);

            return $this->redirectResponse(static::REDIRECT_URL_LIST);
        }

        $productRelationTransfer = $productRelationResponseTransfer->requireProductRelation()
            ->getProductRelation();

        $this->addSuccessMessage(static::MESSAGE_SUCCESS);

        $editProductRelationUrl = Url::generate(
            static::REDIRECT_URL_EDIT,
            [
                EditController::URL_PARAM_ID_PRODUCT_RELATION => $productRelationTransfer->getIdProductRelation(),
            ]
        )->build();

        return $this->redirectResponse($editProductRelationUrl);
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

    /**
     * @param \Generated\Shared\Transfer\ProductRelationResponseTransfer $productRelationResponseTransfer
     *
     * @return void
     */
    protected function processErrorMessages(
        ProductRelationResponseTransfer $productRelationResponseTransfer
    ): void {
        foreach ($productRelationResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }
}
