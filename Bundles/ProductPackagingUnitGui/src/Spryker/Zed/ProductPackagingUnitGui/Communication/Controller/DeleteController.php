<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Controller;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Table\ProductPackagingUnitTypeTableConstantsInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\Business\ProductPackagingUnitGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnitGui\Communication\ProductPackagingUnitGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnitGui\Persistence\ProductPackagingUnitGuiRepositoryInterface getRepository()
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
        $idProductPackagingUnitType = $this->castId($request->query->get(ProductPackagingUnitTypeTableConstantsInterface::REQUEST_ID_PRODUCT_PACKAGING_UNIT_TYPE));
        $productPackagingUnitTypeTransfer = $this->findProductPackagingUnitTypeById($idProductPackagingUnitType);

        if ($this->deleteProductPackagingUnitType($productPackagingUnitTypeTransfer)) {
            $this->addSuccessMessage(sprintf(
                static::MESSAGE_SUCCESS_PACKAGING_UNIT_TYPE_DELETE,
                $productPackagingUnitTypeTransfer->getName()
            ));

            return $this->redirectResponse($this->getSuccessRedirectUrl($request));
        }

        $this->addErrorMessage(sprintf(
            static::MESSAGE_ERROR_PACKAGING_UNIT_TYPE_DELETE,
            $productPackagingUnitTypeTransfer->getName()
        ));

        return $this->redirectResponse($this->getErorrRedirectUrl($idProductPackagingUnitType));
    }

    /**
     * @param int $idProductPackagingUnitType
     *
     * @return string
     */
    protected function getErorrRedirectUrl(
        int $idProductPackagingUnitType
    ): string {
        return Url::generate(
            ProductPackagingUnitTypeTableConstantsInterface::URL_PRODUCT_PACKAGING_UNIT_TYPE_EDIT,
            [ ProductPackagingUnitTypeTableConstantsInterface::REQUEST_ID_PRODUCT_PACKAGING_UNIT_TYPE => $idProductPackagingUnitType]
        )->build();
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
