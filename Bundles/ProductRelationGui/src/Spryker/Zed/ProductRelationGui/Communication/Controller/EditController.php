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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditController extends BaseProductRelationController
{
    public const URL_PARAM_ID_PRODUCT_RELATION = 'id-product-relation';
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_SUCCESS = 'Product relation successfully modified';
    protected const MESSAGE_ACTIVATE_SUCCESS = 'Relation successfully activated.';
    protected const MESSAGE_DEACTIVATE_SUCCESS = 'Relation successfully deactivated.';

    /**
     * @uses \Spryker\Zed\ProductRelationGui\Communication\Controller\EditController::indexAction()
     */
    protected const REDIRECT_URL_EDIT = '/product-relation-gui/edit/index';

    /**
     * @uses \Spryker\Zed\ProductRelationGui\Communication\Controller\ListController::indexAction()
     */
    protected const REDIRECT_URL_LIST = '/product-relation-gui/list/index';

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
            ->createRelationForm(
                $productRelationFormTypeDataProvider->getData($idProductRelation),
                $productRelationFormTypeDataProvider->getOptions(true)
            );

        $productRelationTabs = $this->getFactory()
            ->createProductRelationTabs();

        $productRelationForm->handleRequest($request);

        if ($productRelationForm->isSubmitted() && $productRelationForm->isValid()) {
            return $this->handleSubmitForm($productRelationForm, $idProductRelation);
        }

        $productRelationResponseTransfer = $this->getFactory()
            ->getProductRelationFacade()
            ->findProductRelationById($idProductRelation);

        if (!$productRelationResponseTransfer->getIsSuccessful()) {
            $this->processErrorMessages($productRelationResponseTransfer);

            return $this->redirectResponse(static::REDIRECT_URL_LIST);
        }

        $productRelationResponseTransfer->requireProductRelation();
        $productRelationTransfer = $productRelationResponseTransfer->getProductRelation();

        $productRuleTable = $this->getFactory()
            ->createProductRuleTable($productRelationTransfer);
        $productTable = $this->getFactory()->createProductTable();

        return [
            'productRelationTabs' => $productRelationTabs->createView(),
            'productRelationForm' => $productRelationForm->createView(),
            'productRelation' => $productRelationTransfer,
            'productRuleTable' => $productRuleTable->render(),
            'productTable' => $productTable->render(),
            'productAbstractData' => $this->getProductAbstractData($productRelationTransfer->getFkProductAbstract()),
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request): RedirectResponse
    {
        $productRelationActivateForm = $this->getFactory()->createProductRelationActivateForm()->handleRequest($request);

        return $this->executeRelationStatusChangeAction($request, $productRelationActivateForm, true);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request): RedirectResponse
    {
        $productRelationDeactivateForm = $this->getFactory()->createProductRelationDeactivateForm()->handleRequest($request);

        return $this->executeRelationStatusChangeAction($request, $productRelationDeactivateForm, false);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    protected function getProductAbstractData(int $idProductAbstract): array
    {
        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        return $this->getFactory()
            ->getProductRelationFacade()
            ->getProductAbstractDataById(
                $idProductAbstract,
                $localeTransfer->getIdLocale()
            );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productRelationForm
     * @param int $idProductRelation
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubmitForm(
        FormInterface $productRelationForm,
        int $idProductRelation
    ): RedirectResponse {
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $form
     * @param bool $isActive
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeRelationStatusChangeAction(Request $request, FormInterface $form, bool $isActive): RedirectResponse
    {
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage('CSRF token is not valid.');

            return $this->redirectResponse($redirectUrl);
        }

        $idProductRelation = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_RELATION));

        $productRelationTransfer = $this->getFactory()
            ->getProductRelationFacade()
            ->findProductRelationById($idProductRelation)
            ->getProductRelation();

        if (!$productRelationTransfer) {
            throw new NotFoundHttpException(sprintf('Product relation not found for id %d.', $idProductRelation));
        }

        $productRelationTransfer->setIsActive($isActive);

        $this->getFactory()
            ->getProductRelationFacade()
            ->updateProductRelation($productRelationTransfer);

        $this->addSuccessMessage($isActive ? static::MESSAGE_ACTIVATE_SUCCESS : static::MESSAGE_DEACTIVATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }
}
