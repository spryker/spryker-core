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

    protected const FIELD_REDIRECT_ID_PRODUCT_CONCRETE = 'id-product';
    protected const FIELD_REDIRECT_ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * TODO: ProductAlternativeResponseTransfer also contains messages; check them out later.
     */

    protected const MESSAGE_DELETE_PRODUCT_ALTERNATIVE_SUCCESS = 'Product Alternative was deleted successfully.';
    protected const MESSAGE_DELETE_PRODUCT_ALTERNATIVE_ERROR = 'Product Alternative was not deleted.';

    protected const REDIRECT_URL_PRODUCT_ALTERNATIVE_DELETE = '/product-management/edit/variant?id-product=%d&id-product-abstract=%d&type=#tab-content-alternatives';

    /**
     * TODO: Add ids validation.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAbstractAction(Request $request): RedirectResponse
    {
        $idProductAlternative = $request->query->get(static::FIELD_ID_PRODUCT_ALTERNATIVE);

        $idProduct = $request->query->get(static::FIELD_REDIRECT_ID_PRODUCT_CONCRETE);
        $idProductAbstract = $request->query->get(static::FIELD_REDIRECT_ID_PRODUCT_ABSTRACT);

        /** @var \Generated\Shared\Transfer\ProductAlternativeResponseTransfer $productAlternativeResponseTransfer */
        $productAlternativeResponseTransfer = $this
            ->getFactory()
            ->getProductAlternativeFacade()
            ->deleteProductAlternativeByIdProductAlternativeResponse(
                $idProductAlternative
            );

        $this->handleProductAlternativeDeletion($productAlternativeResponseTransfer);

        return $this->redirectResponse(sprintf(
            static::REDIRECT_URL_PRODUCT_ALTERNATIVE_DELETE,
            $idProduct,
            $idProductAbstract
        ));
    }

    /**
     * TODO: Add ids validation.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteConcreteAction(Request $request): RedirectResponse
    {
        $idProductAlternative = $request->query->get(static::FIELD_ID_PRODUCT_ALTERNATIVE);

        $idProduct = $request->query->get(static::FIELD_REDIRECT_ID_PRODUCT_CONCRETE);
        $idProductAbstract = $request->query->get(static::FIELD_REDIRECT_ID_PRODUCT_ABSTRACT);

        /** @var \Generated\Shared\Transfer\ProductAlternativeResponseTransfer $productAlternativeResponseTransfer */
        $productAlternativeResponseTransfer = $this
            ->getFactory()
            ->getProductAlternativeFacade()
            ->deleteProductAlternativeByIdProductAlternativeResponse(
                $idProductAlternative
            );

        $this->handleProductAlternativeDeletion($productAlternativeResponseTransfer);

        return $this->redirectResponse(sprintf(
            static::REDIRECT_URL_PRODUCT_ALTERNATIVE_DELETE,
            $idProduct,
            $idProductAbstract
        ));
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
}
