<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Controller;

use Generated\Shared\Transfer\ProductOfferConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferValidityTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
 */
class EditOfferController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_PRODUCT_OFFER = 'id_product_offer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): RedirectResponse|array
    {
        $idProductOffer = $this->castId($request->query->get(static::PARAM_ID_PRODUCT_OFFER));

        $productOfferTransfer = $this->getFactory()->getProductOfferFacade()->findOne(
            (new ProductOfferCriteriaTransfer())
                ->setProductOfferConditions(
                    (new ProductOfferConditionsTransfer())
                        ->setProductOfferIds([$idProductOffer]),
                ),
        );

        if (!$productOfferTransfer) {
            $this->addErrorMessage('Product offer not found.');

            return $this->redirectResponse('/product-offer-gui/list');
        }

        $productConcreteTransfer = $this->getFactory()->getProductFacade()->getProductConcrete(
            $productOfferTransfer->getConcreteSkuOrFail(),
        );

        if ($productOfferTransfer->getProductOfferValidity() === null) {
            $productOfferTransfer->setProductOfferValidity(new ProductOfferValidityTransfer());
        }

        $editOfferForm = $this->getFactory()
            ->createEditOfferForm($productOfferTransfer);

        $editOfferForm->handleRequest($request);

        if ($editOfferForm->isSubmitted() && $editOfferForm->isValid()) {
            $productOfferResponseTransfer = $this->getFactory()->getProductOfferFacade()
                ->update($editOfferForm->getData());

            if ($productOfferResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(sprintf('Offer %s has been updated successfully.', $productOfferResponseTransfer->getProductOfferOrFail()->getProductOfferReference()));

                return $this->redirectResponse('/product-offer-gui/list');
            }

            foreach ($productOfferResponseTransfer->getErrors() as $errorTransfer) {
                $this->addErrorMessage($errorTransfer->getMessageOrFail());
            }
        }

        return $this->viewResponse([
            'editOfferForm' => $editOfferForm->createView(),
            'productConcreteTransfer' => $productConcreteTransfer,
            'productOfferTransfer' => $productOfferTransfer,
        ]);
    }
}
