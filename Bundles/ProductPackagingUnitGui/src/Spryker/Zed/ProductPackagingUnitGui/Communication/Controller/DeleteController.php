<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Controller;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\ProductPackagingUnitGui\ProductPackagingUnitGuiConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\Communication\ProductPackagingUnitGuiCommunicationFactory getFactory()
 */
class DeleteController extends AbstractProductPackagingUnitGuiController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idProductPackagingUnitType = $this->castId($request->query->get(ProductPackagingUnitGuiConfig::REQUEST_PARAM_ID_PRODUCT_PACKAGING_UNIT_TYPE));
        $productPackagingUnitTypeTransfer = $this->findProductPackagingUnitTypeById($idProductPackagingUnitType);

        if ($this->deleteProductPackagingUnitType($productPackagingUnitTypeTransfer)) {
            $this->addSuccessMessage(sprintf(
                static::MESSAGE_SUCCESS_PACKAGING_UNIT_TYPE_DELETE,
                $productPackagingUnitTypeTransfer->getName()
            ));

            return $this->redirectResponse(ProductPackagingUnitGuiConfig::URL_PRODUCT_PACKAGING_UNIT_TYPE_LIST);
        }

        $this->addErrorMessage(sprintf(
            static::MESSAGE_ERROR_PACKAGING_UNIT_TYPE_DELETE,
            $productPackagingUnitTypeTransfer->getName()
        ));

        return $this->redirectResponse($this->getErorrRedirectUrl($idProductPackagingUnitType, $request));
    }

    /**
     * @param int $idProductPackagingUnitType
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getErorrRedirectUrl(
        int $idProductPackagingUnitType,
        Request $request
    ): string {
        return $this->getRequestRedirectUrl($request, $this->getEditPageForId($idProductPackagingUnitType));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return bool
     */
    protected function deleteProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): bool {
        return $this->getFactory()
            ->getProductPackagingUnitFacade()
            ->deleteProductPackagingUnitType($productPackagingUnitTypeTransfer);
    }
}
