<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Controller;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Table\ProductPackagingUnitTypeTableConstantsInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\Business\ProductPackagingUnitGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnitGui\Communication\ProductPackagingUnitGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnitGui\Persistence\ProductPackagingUnitGuiRepositoryInterface getRepository()
 */
class EditController extends AbstractProductPackagingUnitGuiController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductPackagingUnitType = $this->castId($request->query->get(ProductPackagingUnitTypeTableConstantsInterface::REQUEST_ID_PRODUCT_PACKAGING_UNIT_TYPE));
        $availableLocales = $this->getFactory()->getLocaleFacade()->getLocaleCollection();

        $productPackagingUnitTypeDataProvider = $this->getFactory()
            ->createProductPackagingUnitTypeDataProvider();

        $productPackagingUnitTypeTransfer = $productPackagingUnitTypeDataProvider->getData($idProductPackagingUnitType);
        $allowDelete = $this->countProductPackagingUnitsByTypeId($productPackagingUnitTypeTransfer) > 0;

        $productPackagingUnitTypeForm = $this->getFactory()
            ->getProductPackagingUnitTypeForm(
                $productPackagingUnitTypeTransfer,
                $productPackagingUnitTypeDataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($productPackagingUnitTypeForm->isSubmitted() && $productPackagingUnitTypeForm->isValid()) {
            $this->updateProductPackagingUnitType($request, $productPackagingUnitTypeForm);
        }

        return $this->viewResponse([
            'availableLocales' => $availableLocales,
            'productPackagingUnitTypeForm' => $productPackagingUnitTypeForm->createView(),
            'productPackagingUnitTypeTransfer' => $productPackagingUnitTypeTransfer,
            'allowDelete' => $allowDelete,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return int
     */
    protected function countProductPackagingUnitsByTypeId(ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer): int
    {
        return $this->getFactory()
            ->getProductPackagingUnitFacade()
            ->countProductPackagingUnitsByTypeId($productPackagingUnitTypeTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $productPackagingUnitTypeForm
     *
     * @return void
     */
    protected function updateProductPackagingUnitType(Request $request, FormInterface $productPackagingUnitTypeForm): void
    {
        /** @var \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer */
        $productPackagingUnitTypeTransfer = $productPackagingUnitTypeForm->getData();
        try {
            $productPackagingUnitTypeTransfer = $this->getFactory()
                ->getProductPackagingUnitFacade()
                ->updateProductPackagingUnitType($productPackagingUnitTypeTransfer);
        } catch (Throwable $throwable) {
            $this->addErrorMessage(sprintf(
                static::MESSAGE_ERROR_PACKAGING_UNIT_TYPE_UPDATE,
                $productPackagingUnitTypeTransfer->getName()
            ));

            return;
        }

        $this->addSuccessMessage(sprintf(
            static::MESSAGE_SUCCESS_PACKAGING_UNIT_TYPE_UPDATE,
            $productPackagingUnitTypeTransfer->getName()
        ));
    }
}
