<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Controller;

use Generated\Shared\Transfer\ProductLabelResponseTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductLabelGui\Communication\ProductLabelGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface getQueryContainer()
 */
class DeleteController extends AbstractController
{
    public const URL_PARAM_ID_PRODUCT_LABEL = 'id-product-label';
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_SUCCESS = 'Product label #%d was successfully deleted.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductLabel = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_LABEL));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);

        $productLabelDeleteForm = $this->getFactory()->createProductLabelDeleteForm();
        $productLabelDeleteForm->handleRequest($request);
        if ($productLabelDeleteForm->isSubmitted() && $productLabelDeleteForm->isValid()) {
            return $this->handleSubmitForm($idProductLabel, $redirectUrl);
        }

        return $this->viewResponse([
            'deleteProductLabelForm' => $productLabelDeleteForm->createView(),
        ]);
    }

    /**
     * @param int $idProductLabel
     * @param string $redirectUrl
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubmitForm(
        int $idProductLabel,
        string $redirectUrl
    ): RedirectResponse {
        $productLabelTransfer = (new ProductLabelTransfer())->setIdProductLabel($idProductLabel);

        $productLabelResponseTransfer = $this->getFactory()
            ->getProductLabelFacade()
            ->removeLabel($productLabelTransfer);

        if (!$productLabelResponseTransfer->getIsSuccessful()) {
            $this->processErrorMessages($productLabelResponseTransfer);

            return $this->redirectResponse($redirectUrl);
        }

        $this->addSuccessMessage(sprintf(static::MESSAGE_SUCCESS, $idProductLabel));

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelResponseTransfer $productLabelResponseTransfer
     *
     * @return void
     */
    protected function processErrorMessages(ProductLabelResponseTransfer $productLabelResponseTransfer): void
    {
        foreach ($productLabelResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }
}
