<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Controller;

use Generated\Shared\Transfer\ProductRelationResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductRelationGui\Communication\ProductRelationGuiCommunicationFactory getFactory()
 */
class DeleteController extends AbstractController
{
    public const URL_PARAM_ID_PRODUCT_RELATION = 'id-product-relation';
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_SUCCESS = 'Relation #%d successfully deleted.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductRelation = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_RELATION));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);
        $productRelationDeleteForm = $this->getFactory()->createProductRelationDeleteForm();
        $productRelationDeleteForm->handleRequest($request);

        if ($productRelationDeleteForm->isSubmitted() && $productRelationDeleteForm->isValid()) {
            return $this->handleSubmitForm($idProductRelation, $redirectUrl);
        }

        return $this->viewResponse([
            'deleteProductRelationForm' => $productRelationDeleteForm->createView(),
        ]);
    }

    /**
     * @param int $idProductRelation
     * @param string $redirectUrl
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubmitForm(
        int $idProductRelation,
        string $redirectUrl
    ): RedirectResponse {
        $productRelationResponseTransfer = $this->getFactory()
            ->getProductRelationFacade()
            ->deleteProductRelation($idProductRelation);

        if (!$productRelationResponseTransfer->getIsSuccessful()) {
            $this->processErrorMessages($productRelationResponseTransfer);

            return $this->redirectResponse($redirectUrl);
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS, [
            '%d' => $idProductRelation,
        ]);

        return $this->redirectResponse($redirectUrl);
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
