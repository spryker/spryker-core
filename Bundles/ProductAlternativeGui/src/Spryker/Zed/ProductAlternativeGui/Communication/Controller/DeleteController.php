<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Communication\Controller;

use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductAlternativeGui\Communication\ProductAlternativeGuiCommunicationFactory getFactory()
 */
class DeleteController extends AbstractController
{
    protected const FIELD_ID_PRODUCT_ALTERNATIVE = 'id-product-alternative';

    protected const MESSAGE_DELETE_PRODUCT_ALTERNATIVE_SUCCESS = 'Product Alternative was deleted successfully.';
    protected const MESSAGE_DELETE_PRODUCT_ALTERNATIVE_ERROR = 'Product Alternative was not deleted.';

    protected const KEY_TAB_PRODUCT_ALTERNATIVE = '#tab-content-alternatives';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAlternativeAction(Request $request): RedirectResponse
    {
        $idProductAlternative = $this->castId($request->get(static::FIELD_ID_PRODUCT_ALTERNATIVE));

        $productAlternativeResponseTransfer = $this->getFactory()
            ->getProductAlternativeFacade()
            ->deleteProductAlternativeByIdProductAlternative(
                $idProductAlternative
            );

        $this->handleProductAlternativeDeletion($productAlternativeResponseTransfer);

        return $this->redirectToReferer($request);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeResponseTransfer $productAlternativeResponseTransfer
     *
     * @return void
     */
    protected function handleProductAlternativeDeletion(ProductAlternativeResponseTransfer $productAlternativeResponseTransfer): void
    {
        if ($productAlternativeResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(
                static::MESSAGE_DELETE_PRODUCT_ALTERNATIVE_SUCCESS
            );

            return;
        }

        $this->addErrorMessage(static::MESSAGE_DELETE_PRODUCT_ALTERNATIVE_ERROR);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToReferer(Request $request): RedirectResponse
    {
        return $this->redirectResponse($request->headers->get('referer') . static::KEY_TAB_PRODUCT_ALTERNATIVE);
    }
}
