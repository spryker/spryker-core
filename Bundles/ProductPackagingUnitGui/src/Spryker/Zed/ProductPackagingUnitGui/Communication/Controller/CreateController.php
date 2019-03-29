<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Controller;

use Spryker\Zed\ProductPackagingUnit\Business\Exception\ProductPackagingUnitTypeUniqueViolationException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\Communication\ProductPackagingUnitGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractProductPackagingUnitGuiController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $availableLocales = $this->getFactory()->getLocaleFacade()->getLocaleCollection();
        $productPackagingUnitTypeDataProvider = $this->getFactory()
            ->createProductPackagingUnitTypeDataProvider();

        $productPackagingUnitTypeForm = $this->getFactory()
            ->getProductPackagingUnitTypeForm(
                $productPackagingUnitTypeDataProvider->getData(),
                $productPackagingUnitTypeDataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($productPackagingUnitTypeForm->isSubmitted() && $productPackagingUnitTypeForm->isValid()) {
            return $this->createProductPackagingUnitType($request, $productPackagingUnitTypeForm, $availableLocales);
        }

        return $this->viewResponse([
            'availableLocales' => $availableLocales,
            'productPackagingUnitTypeForm' => $productPackagingUnitTypeForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $productPackagingUnitTypeForm
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $availableLocales
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createProductPackagingUnitType(Request $request, FormInterface $productPackagingUnitTypeForm, array $availableLocales)
    {
        $redirectUrl = $this->getRequestRedirectUrl($request);
        $productPackagingUnitTypeTransfer = $productPackagingUnitTypeForm->getData();

        try {
            $productPackagingUnitTypeTransfer = $this->getFactory()
                ->getProductPackagingUnitFacade()
                ->createProductPackagingUnitType($productPackagingUnitTypeTransfer);

            if (!$productPackagingUnitTypeTransfer->getIdProductPackagingUnitType()) {
                $this->addErrorMessage(static::MESSAGE_ERROR_PACKAGING_UNIT_TYPE_CREATE);

                return $this->redirectResponse($redirectUrl);
            }
        } catch (ProductPackagingUnitTypeUniqueViolationException $exception) {
            $this->addErrorMessage($exception->getMessage());

            return $this->redirectResponse($redirectUrl);
        } catch (Throwable $exception) {
            $this->addErrorMessage(static::MESSAGE_ERROR_PACKAGING_UNIT_TYPE_CREATE);

            return $this->viewResponse([
                'availableLocales' => $availableLocales,
                'productPackagingUnitTypeForm' => $productPackagingUnitTypeForm->createView(),
            ]);
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_PACKAGING_UNIT_TYPE_CREATE);

        return $this->redirectResponse($redirectUrl);
    }
}
