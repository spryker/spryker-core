<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\ProductConcreteNotFoundException;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class UpdateProductConcreteController extends UpdateProductController
{
    protected const PARAM_PRODUCT_ID = 'product-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\ProductConcreteNotFoundException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idProduct = $this->castId($request->get(static::PARAM_PRODUCT_ID));
        $formData = $this->getFactory()->createProductConcreteEditFormDataProvider()->getData($idProduct);

        if (!$formData[ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE]) {
            throw new ProductConcreteNotFoundException($idProduct);
        }

        $formOptions = $this->getFactory()->createProductConcreteEditFormDataProvider()->getOptions();
        $productConcreteTransfer = $formData[ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE];

        $productConcreteEditForm = $this->getFactory()->createProductConcreteEditForm($formData, $formOptions);
        $productConcreteEditForm->handleRequest($request);
        $initialData = $this->getDefaultInitialData($request->get($productConcreteEditForm->getName())[ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE]);

        if (!$productConcreteEditForm->isSubmitted()) {
            return $this->getResponse($productConcreteEditForm, $productConcreteTransfer, new ValidationResponseTransfer(), $initialData);
        }

        return $this->executeProductConcreteEditFormSubmission($productConcreteEditForm, $initialData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function priceTableDataAction(Request $request): Response
    {
        $idProductConcrete = $this->castId($request->get(PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE));

        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createPriceProductConcreteTableDataProvider($idProductConcrete),
            $this->getFactory()->createPriceProductConcreteGuiTableConfigurationProvider()->getConfiguration($idProductConcrete)
        );
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productConcreteEditForm
     *
     * @param \Symfony\Component\Form\FormInterface $productConcreteEditForm
     * @param mixed[] $initialData
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function executeProductConcreteEditFormSubmission(
        FormInterface $productConcreteEditForm,
        array $initialData
    ): JsonResponse {
        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $productConcreteEditForm->getData()[ProductConcreteForm::BLOCK_PREFIX];
        $pricesValidationResponseTransfer = $this->getFactory()
            ->getPriceProductFacade()
            ->validatePrices($productConcreteTransfer->getPrices());
        $merchantProductValidationResponseTransfer = new ValidationResponseTransfer();

        if ($productConcreteEditForm->isValid() && $pricesValidationResponseTransfer->getIsSuccess()) {
            $idMerchant = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getIdMerchantOrFail();
            $merchantProductValidationResponseTransfer = $this->getFactory()
                ->getMerchantProductFacade()->validateMerchantProduct(
                    (new MerchantProductTransfer())->setIdMerchant($idMerchant)->setProductAbstract(
                        (new ProductAbstractTransfer())->setIdProductAbstract($productConcreteTransfer->getFkProductAbstractOrFail())
                    )
                );

            if ($productConcreteEditForm->getData()[ProductConcreteEditForm::FIELD_USE_ABSTRACT_PRODUCT_PRICES]) {
                $priceProductTransfers = $this->getFactory()->getPriceProductFacade()->findProductConcretePricesWithoutPriceExtraction(
                    $productConcreteTransfer->getIdProductConcreteOrFail(),
                    $productConcreteTransfer->getFkProductAbstractOrFail()
                );
                foreach ($priceProductTransfers as $priceProductTransfer) {
                    $this->getFactory()->getPriceProductFacade()->removePriceProductDefaultForPriceProduct($priceProductTransfer);
                }
                $productConcreteTransfer->setPrices(new ArrayObject());
            }

            if ($merchantProductValidationResponseTransfer->getIsSuccess()) {
                $this->getFactory()->getProductFacade()->saveProductConcrete($productConcreteTransfer);
            }
        }

        $initialData = $this->getFactory()->createPriceProductMapper()->mapValidationResponseTransferToInitialDataErrors(
            $pricesValidationResponseTransfer,
            $initialData
        );

        return $this->getResponse($productConcreteEditForm, $productConcreteTransfer, $merchantProductValidationResponseTransfer, $initialData);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productConcreteEditForm
     *
     * @param \Symfony\Component\Form\FormInterface $productConcreteEditForm
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     * @param mixed[] $initialData
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productConcreteEditForm,
        ProductConcreteTransfer $productConcreteTransfer,
        ValidationResponseTransfer $validationResponseTransfer,
        array $initialData
    ): JsonResponse {
        $localeTransfer = $this->getFactory()->getLocaleFacade()->getCurrentLocale();
        $localizedAttributesTransfer = $this->getFactory()->createLocalizedAttributesExtractor()->extractLocalizedAttributes(
            $productConcreteTransfer->getLocalizedAttributes(),
            $localeTransfer
        );
        $superAttributeNames = $this->getFactory()->createLocalizedAttributesExtractor()->extractCombinedSuperAttributeNames(
            $productConcreteTransfer->getAttributes(),
            $productConcreteTransfer->getLocalizedAttributes(),
            $localeTransfer
        );
        $reservationResponseTransfer = $this->getFactory()->getOmsFacade()->getOmsReservedProductQuantity(
            (new ReservationRequestTransfer())
                ->setSku($productConcreteTransfer->getSku())
                ->setStore($this->getFactory()->getStoreFacade()->getCurrentStore())
        );

        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/product_concrete_form.twig', [
                'form' => $productConcreteEditForm->createView(),
                'productConcrete' => $productConcreteTransfer,
                'productConcreteName' => $localizedAttributesTransfer ? $localizedAttributesTransfer->getName() : $productConcreteTransfer->getName(),
                'productAttributeTableConfiguration' => $this->getFactory()
                    ->createProductConcreteAttributeGuiTableConfigurationProvider()
                    ->getConfiguration($productConcreteTransfer->getAttributes(), array_keys($superAttributeNames), $productConcreteTransfer->getLocalizedAttributes()),
                'superAttributeNames' => $superAttributeNames,
                'priceProductConcreteTableConfiguration' => $this->getFactory()
                    ->createPriceProductConcreteGuiTableConfigurationProvider()
                    ->getConfiguration($productConcreteTransfer->getIdProductConcreteOrFail(), $initialData),
                'reservedStock' => $reservationResponseTransfer->getReservationQuantityOrFail()->toFloat(),
            ])->getContent(),
        ];

        if (!$productConcreteEditForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if ($productConcreteEditForm->isValid() && $validationResponseTransfer->getIsSuccess()) {
            $responseData = $this->addSuccessResponseDataToResponse($responseData);

            return new JsonResponse($responseData);
        }

        if (!$productConcreteEditForm->isValid()) {
            $responseData = $this->addErrorResponseDataToResponse($responseData);
        }

        if (!$validationResponseTransfer->getIsSuccess()) {
            $responseData = $this->addValidationResponseMessagesToResponse($responseData, $validationResponseTransfer);
        }

        return new JsonResponse($responseData);
    }
}
