<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\ProductConcreteNotFoundException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class UpdateProductConcreteController extends AbstractUpdateProductController
{
    protected const PARAM_PRODUCT_ID = 'product-id';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteForm::BLOCK_PREFIX
     */
    protected const BLOCK_PREFIX_PRODUCT_CONCRETE_FORM = 'productConcrete';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_USE_ABSTRACT_PRODUCT_PRICES
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES = 'useAbstractProductPrices';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_PRODUCT_CONCRETE = 'productConcrete';

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
        $productConcreteEditFormDataProvider = $this->getFactory()->createProductConcreteEditFormDataProvider();
        $formData = $productConcreteEditFormDataProvider->getData($idProduct);

        if (!$formData[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_PRODUCT_CONCRETE]) {
            throw new ProductConcreteNotFoundException($idProduct);
        }

        $formOptions = $productConcreteEditFormDataProvider->getOptions();
        $productConcreteTransfer = $formData[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_PRODUCT_CONCRETE];

        $productConcreteEditForm = $this->getFactory()->createProductConcreteEditForm($formData, $formOptions);
        $productConcreteEditForm->handleRequest($request);
        $initialData = $this->getDefaultInitialData(
            $request->get($productConcreteEditForm->getName())[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_PRODUCT_CONCRETE]
        );

        if ($productConcreteEditForm->isSubmitted()) {
            return $this->handleProductConcreteEditFormSubmission($productConcreteEditForm, $initialData);
        }

        return $this->getResponse(
            $productConcreteEditForm,
            $productConcreteTransfer,
            new ValidationResponseTransfer(),
            $initialData
        );
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
    protected function handleProductConcreteEditFormSubmission(
        FormInterface $productConcreteEditForm,
        array $initialData
    ): JsonResponse {
        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $productConcreteEditForm->getData()[static::BLOCK_PREFIX_PRODUCT_CONCRETE_FORM];

        $pricesValidationResponseTransfer = $this->getFactory()
            ->getPriceProductFacade()
            ->validatePrices($productConcreteTransfer->getPrices());
        $merchantProductValidationResponseTransfer = new ValidationResponseTransfer();

        $initialData = $this->getFactory()
            ->createPriceProductMapper()
            ->mapValidationResponseTransferToInitialDataErrors($pricesValidationResponseTransfer, $initialData);

        if ($productConcreteEditForm->isValid() && $pricesValidationResponseTransfer->getIsSuccess()) {
            $merchantProductValidationResponseTransfer = $this->validateMerchantProduct(
                $this->getIdMerchantFromCurrentUser(),
                $productConcreteTransfer->getFkProductAbstractOrFail()
            );

            if ($merchantProductValidationResponseTransfer->getIsSuccess()) {
                $this->saveProductConcreteData($productConcreteEditForm, $productConcreteTransfer);
            }
        }

        return $this->getResponse(
            $productConcreteEditForm,
            $productConcreteTransfer,
            $merchantProductValidationResponseTransfer,
            $initialData
        );
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

    /**
     * @param int $idMerchant
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    protected function validateMerchantProduct(int $idMerchant, int $idProductAbstract): ValidationResponseTransfer
    {
        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setIdProductAbstract($idProductAbstract);

        $merchantProductTransfer = (new MerchantProductTransfer())
            ->setIdMerchant($idMerchant)
            ->setProductAbstract($productAbstractTransfer);

        return $this->getFactory()
            ->getMerchantProductFacade()
            ->validateMerchantProduct($merchantProductTransfer);
    }

    /**
     * @return int
     */
    protected function getIdMerchantFromCurrentUser(): int
    {
        return $this->getFactory()
            ->getMerchantUserFacade()
            ->getCurrentMerchantUser()
            ->getIdMerchantOrFail();
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productConcreteEditForm
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function saveProductConcreteData(
        FormInterface $productConcreteEditForm,
        ProductConcreteTransfer $productConcreteTransfer
    ): void {
        if ($productConcreteEditForm->getData()[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES]) {
            $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
                ->setIdProductConcrete($productConcreteTransfer->getIdProductConcreteOrFail());

            $priceProductTransfers = $this->getFactory()
                ->getPriceProductFacade()
                ->findProductConcretePricesWithoutPriceExtraction(
                    $productConcreteTransfer->getIdProductConcreteOrFail(),
                    $productConcreteTransfer->getFkProductAbstractOrFail(),
                    $priceProductCriteriaTransfer
                );

            foreach ($priceProductTransfers as $priceProductTransfer) {
                $this->getFactory()
                    ->getPriceProductFacade()
                    ->removePriceProductDefaultForPriceProduct($priceProductTransfer);
            }

            $productConcreteTransfer->setPrices(new ArrayObject());
        }

        $this->getFactory()->getProductFacade()->saveProductConcrete($productConcreteTransfer);
    }
}
