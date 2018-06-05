<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Communication\Controller;

use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductAlternativeGui\Communication\ProductAlternativeGuiCommunicationFactory getFactory()
 */
class DeleteController extends AbstractController
{
    protected const FIELD_ID_PRODUCT = 'id-product';
    protected const FIELD_ID_ABSTRACT_ALTERNATIVE = 'id-abstract-alternative';
    protected const FIELD_ID_CONCRETE_ALTERNATIVE = 'id-concrete-alternative';

    protected const MESSAGE_PRODUCT_ALTERNATIVE_DELETE_SUCCESS = 'Product Alternative was deleted successfully.';
    protected const MESSAGE_PRODUCT_ALTERNATIVE_DELETE_ERROR = 'Product Alternative was not deleted.';

    protected const REDIRECT_URL = '/product-management/edit/variant?id-product=%d&id-product-abstract=%d';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function deleteAbstractAction(Request $request): array
    {
        $idProduct = $request->query->get(static::FIELD_ID_PRODUCT);
        $idProductAbstractAlternative = $request->query->get(static::FIELD_ID_ABSTRACT_ALTERNATIVE);

        /** @var \Generated\Shared\Transfer\ProductAlternativeResponseTransfer $productAlternativeResponseTransfer */
        $productAlternativeResponseTransfer = $this
            ->getFactory()
            ->getProductAlternativeFacade()
            ->deleteProductAbstractAlternative(
                $idProduct,
                $idProductAbstractAlternative
            );

        return $this->handleProductAlternativeDeletion($productAlternativeResponseTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function deleteConcreteAction(Request $request): array
    {
        $idProduct = $request->query->get(static::FIELD_ID_PRODUCT);
        $idProductConcreteAlternative = $request->query->get(static::FIELD_ID_CONCRETE_ALTERNATIVE);

        /** @var \Generated\Shared\Transfer\ProductAlternativeResponseTransfer $productAlternativeResponseTransfer */
        $productAlternativeResponseTransfer = $this
            ->getFactory()
            ->getProductAlternativeFacade()
            ->deleteProductConcreteAlternative(
                $idProduct,
                $idProductConcreteAlternative
            );

        return $this->handleProductAlternativeDeletion($productAlternativeResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeResponseTransfer $productAlternativeResponseTransfer
     *
     * @return array
     */
    protected function handleProductAlternativeDeletion(ProductAlternativeResponseTransfer $productAlternativeResponseTransfer): array
    {
        if ($productAlternativeResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(
                static::MESSAGE_PRODUCT_ALTERNATIVE_DELETE_SUCCESS
            );

            return [
                'productAlternative' => $productAlternativeResponseTransfer
                    ->getProductAlternative(),
            ];
        }

        $this->addErrorMessage(static::MESSAGE_PRODUCT_ALTERNATIVE_DELETE_ERROR);

        return [];
    }
}
