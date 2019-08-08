<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Controller;

use Spryker\Zed\ProductPackagingUnitGui\ProductPackagingUnitGuiConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\Communication\ProductPackagingUnitGuiCommunicationFactory getFactory()
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
        $idProductPackagingUnitType = $this->castId($request->query->get(ProductPackagingUnitGuiConfig::REQUEST_PARAM_ID_PRODUCT_PACKAGING_UNIT_TYPE));
        $availableLocales = $this->getFactory()->getLocaleFacade()->getLocaleCollection();

        $productPackagingUnitTypeDataProvider = $this->getFactory()
            ->createProductPackagingUnitTypeDataProvider();

        $productPackagingUnitTypeTransfer = $productPackagingUnitTypeDataProvider->getData($idProductPackagingUnitType);

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
        ]);
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
            $this->addErrorMessage(static::MESSAGE_ERROR_PACKAGING_UNIT_TYPE_UPDATE, [
                '%s' => $productPackagingUnitTypeTransfer->getName(),
            ]);

            return;
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_PACKAGING_UNIT_TYPE_UPDATE, [
            '%s' => $productPackagingUnitTypeTransfer->getName(),
        ]);
    }
}
