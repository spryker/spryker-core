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
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use SplObjectStorage;
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
    /**
     * @var string
     */
    protected const PARAM_PRODUCT_ID = 'product-id';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteForm::BLOCK_PREFIX
     *
     * @var string
     */
    protected const BLOCK_PREFIX_PRODUCT_CONCRETE_FORM = 'productConcrete';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_USE_ABSTRACT_PRODUCT_PRICES
     *
     * @var string
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES = 'useAbstractProductPrices';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_USE_ABSTRACT_PRODUCT_NAME
     *
     * @var string
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_NAME = 'useAbstractProductName';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_USE_ABSTRACT_PRODUCT_DESCRIPTION
     *
     * @var string
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_DESCRIPTION = 'useAbstractProductDescription';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_USE_ABSTRACT_PRODUCT_IMAGE_SETS
     *
     * @var string
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_IMAGE_SETS = 'useAbstractProductImageSets';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE
     *
     * @var string
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_PRODUCT_CONCRETE = 'productConcrete';

    /**
     * @var string
     */
    protected const DEFAULT_LOCALE = 'Default';

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

        $storedProductAttributes = $productConcreteTransfer->getAttributes();

        $productConcreteEditForm->handleRequest($request);

        $pricesInitialData = $this->getDefaultInitialData(
            PriceProductTableViewTransfer::PRICES,
            $request->get($productConcreteEditForm->getName())[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_PRODUCT_CONCRETE] ?? null,
        );

        $attributesInitialData = $this->getDefaultInitialData(
            ProductConcreteTransfer::ATTRIBUTES,
            $request->get($productConcreteEditForm->getName())[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_PRODUCT_CONCRETE] ?? null,
        );

        if ($productConcreteEditForm->isSubmitted()) {
            $productConcreteTransfer->setAttributes($storedProductAttributes);

            return $this->handleProductConcreteEditFormSubmission(
                $productConcreteEditForm,
                $pricesInitialData,
                $attributesInitialData,
            );
        }

        return $this->getResponse(
            $productConcreteEditForm,
            $productConcreteTransfer,
            new ValidationResponseTransfer(),
            $pricesInitialData,
            $attributesInitialData,
            [],
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

        return $this->getFactory()
            ->getGuiTableHttpDataRequestExecutor()
            ->execute(
                $request,
                $this->getFactory()
                    ->createPriceProductConcreteTableDataProvider($idProductConcrete),
                $this->getFactory()
                    ->createPriceProductConcreteGuiTableConfigurationProvider()
                    ->getConfiguration($idProductConcrete),
            );
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productConcreteEditForm
     *
     * @param \Symfony\Component\Form\FormInterface $productConcreteEditForm
     * @param array $pricesInitialData
     * @param array $attributesInitialData
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function handleProductConcreteEditFormSubmission(
        FormInterface $productConcreteEditForm,
        array $pricesInitialData,
        array $attributesInitialData
    ): JsonResponse {
        $imageSetsErrors = [];

        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $productConcreteEditForm->getData()[static::BLOCK_PREFIX_PRODUCT_CONCRETE_FORM];
        $priceProductTransfers = $productConcreteTransfer->getPrices();

        $priceProductTransfers = $priceProductTransfers->getArrayCopy();
        $priceProductTransfers = array_values($priceProductTransfers);
        $priceProductTransfers = new ArrayObject($priceProductTransfers);

        $pricesValidationResponseTransfer = $this->getFactory()
            ->getPriceProductFacade()
            ->validatePrices($priceProductTransfers);

        $merchantProductValidationResponseTransfer = (new ValidationResponseTransfer())->setIsSuccess(true);

        $pricesInitialData = $this->getFactory()
            ->createPriceProductValidationMapper()
            ->mapValidationResponseTransferToInitialData(
                $pricesValidationResponseTransfer,
                $priceProductTransfers,
                $pricesInitialData,
            );

        if ($productConcreteEditForm->isValid() && $pricesValidationResponseTransfer->getIsSuccess()) {
            $merchantProductValidationResponseTransfer = $this->validateMerchantProduct(
                $this->getIdMerchantFromCurrentUser(),
                $productConcreteTransfer->getFkProductAbstractOrFail(),
            );

            $productAttributes = $this->getFactory()
                ->createProductAttributesMapper()
                ->mapAttributesDataToProductAttributes(
                    $attributesInitialData,
                    $productConcreteTransfer->getAttributes(),
                );
            $productLocalizedAttributes = $this->getFactory()
                ->createProductAttributesMapper()
                ->mapAttributesDataToLocalizedAttributesTransfers(
                    $attributesInitialData,
                    $productConcreteTransfer->getLocalizedAttributes(),
                );

            $productConcreteTransfer->setAttributes($productAttributes)->setLocalizedAttributes($productLocalizedAttributes);

            $attributesInitialData = [];

            if ($merchantProductValidationResponseTransfer->getIsSuccess()) {
                $productAbstractTransfer = $this->getFactory()
                    ->getProductFacade()
                    ->findProductAbstractById($productConcreteTransfer->getFkProductAbstractOrFail());

                $this->saveProductConcreteData($productConcreteEditForm, $productConcreteTransfer, $productAbstractTransfer);
            }
        } else {
            $errors = $productConcreteEditForm->getErrors(true, false);

            $imageSetsErrors = $this->getFactory()
                ->createImageSetMapper()
                ->mapErrorsToImageSetValidationData(
                    $productConcreteEditForm->getErrors(true, true),
                );

            $attributesInitialData = $this->getFactory()
                ->createProductAttributesMapper()
                ->mapErrorsToAttributesData($errors, $attributesInitialData);
        }

        $merchantProductValidationResponseTransfer->setIsSuccess(
            $pricesValidationResponseTransfer->getIsSuccessOrFail()
            && $merchantProductValidationResponseTransfer->getIsSuccessOrFail(),
        );

        return $this->getResponse(
            $productConcreteEditForm,
            $productConcreteTransfer,
            $merchantProductValidationResponseTransfer,
            $pricesInitialData,
            $attributesInitialData,
            $imageSetsErrors,
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productConcreteEditForm
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     * @param array $priceInitialData
     * @param array $attributesInitialData
     * @param array $imageSetsErrors
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productConcreteEditForm,
        ProductConcreteTransfer $productConcreteTransfer,
        ValidationResponseTransfer $validationResponseTransfer,
        array $priceInitialData,
        array $attributesInitialData,
        array $imageSetsErrors
    ): JsonResponse {
        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();
        $localizedAttributesTransfer = $this->getFactory()
            ->createLocalizedAttributesExtractor()
            ->extractLocalizedAttributes(
                $productConcreteTransfer->getLocalizedAttributes(),
                $localeTransfer,
            );
        $superAttributeNames = $this->getFactory()
            ->createLocalizedAttributesExtractor()
            ->extractCombinedSuperAttributeNames(
                $productConcreteTransfer->getAttributes(),
                $productConcreteTransfer->getLocalizedAttributes(),
                $localeTransfer,
            );
        $reservationResponseTransfer = $this->getFactory()
            ->getOmsFacade()
            ->getOmsReservedProductQuantity(
                (new ReservationRequestTransfer())
                    ->setSku($productConcreteTransfer->getSku())
                    ->setStore($this->getFactory()->getStoreFacade()->getCurrentStore()),
            );

        $imageSetTabNames = $this->getImageSetTabNames($productConcreteTransfer);
        $imageSetsGroupedByIdLocale = $this->getImageSetsGroupedByIdLocale($productConcreteTransfer->getImageSets());
        $imageSetMetaData = $this->getImageSetMetaDataGroupedByImageSet($productConcreteTransfer->getImageSets(), $imageSetsErrors);

        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/product_concrete_form.twig', [
                'form' => $productConcreteEditForm->createView(),
                'productConcrete' => $productConcreteTransfer,
                'imageSetTabNames' => $imageSetTabNames,
                'imageSetsGroupedByIdLocale' => $imageSetsGroupedByIdLocale,
                'imageSetMetaData' => $imageSetMetaData,
                'productConcreteName' => $localizedAttributesTransfer ? $localizedAttributesTransfer->getName() : $productConcreteTransfer->getName(),
                'superAttributeNames' => $superAttributeNames,
                'priceProductConcreteTableConfiguration' => $this->getFactory()
                    ->createPriceProductConcreteGuiTableConfigurationProvider()
                    ->getConfiguration($productConcreteTransfer->getIdProductConcreteOrFail(), $priceInitialData),
                'productAttributeTableConfiguration' => $this->getFactory()
                    ->createProductConcreteAttributeGuiTableConfigurationProvider()
                    ->getConfiguration($productConcreteTransfer->getIdProductConcreteOrFail(), $attributesInitialData),
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

        return new JsonResponse(
            $this->addErrorResponseDataToResponse(
                $productConcreteEditForm,
                $validationResponseTransfer,
                $responseData,
            ),
        );
    }

    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $imageSets
     *
     * @phpstan-return array<int, array<int, \Generated\Shared\Transfer\ProductImageSetTransfer>>
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $imageSets
     *
     * @return array<\Generated\Shared\Transfer\LocalizedAttributesTransfer>
     */
    protected function getImageSetsGroupedByIdLocale(ArrayObject $imageSets): array
    {
        $imageSetsGroupedByIdLocale = [];

        foreach ($imageSets as $imageSet) {
            $idLocale = $imageSet->getLocale() ? $imageSet->getLocaleOrFail()->getIdLocaleOrFail() : 0;
            $imageSetsGroupedByIdLocale[$idLocale][] = $imageSet;
        }

        return $imageSetsGroupedByIdLocale;
    }

    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $imageSets
     * @phpstan-param array<int, mixed> $imageSetsErrors
     *
     * @phpstan-return \SplObjectStorage<object, mixed>
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $imageSets
     * @param array $imageSetsErrors
     *
     * @return \SplObjectStorage
     */
    protected function getImageSetMetaDataGroupedByImageSet(ArrayObject $imageSets, array $imageSetsErrors): SplObjectStorage
    {
        $imageSetMetaData = new SplObjectStorage();

        foreach ($imageSets as $originalIndex => $imageSet) {
            $imageSetMetaData[$imageSet] = [
                'originalIndex' => $originalIndex,
                'errors' => $imageSetsErrors[$originalIndex] ?? [],
            ];
        }

        return $imageSetMetaData;
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
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|null $productAbstractTransfer
     *
     * @return void
     */
    protected function saveProductConcreteData(
        FormInterface $productConcreteEditForm,
        ProductConcreteTransfer $productConcreteTransfer,
        ?ProductAbstractTransfer $productAbstractTransfer
    ): void {
        $data = $productConcreteEditForm->getData();
        if ($data[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES]) {
            $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
                ->setIdProductConcrete($productConcreteTransfer->getIdProductConcreteOrFail());

            $priceProductTransfers = $this->getFactory()
                ->getPriceProductFacade()
                ->findProductConcretePricesWithoutPriceExtraction(
                    $productConcreteTransfer->getIdProductConcreteOrFail(),
                    $productConcreteTransfer->getFkProductAbstractOrFail(),
                    $priceProductCriteriaTransfer,
                );

            foreach ($priceProductTransfers as $priceProductTransfer) {
                $this->getFactory()
                    ->getPriceProductFacade()
                    ->removePriceProductDefaultForPriceProduct($priceProductTransfer);
            }

            $productConcreteTransfer->setPrices(new ArrayObject());
        }

        if ($productAbstractTransfer) {
            if ($data[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_NAME]) {
                $localizedAttributeTransfers = $this->getFactory()
                    ->createProductAttributesMapper()
                    ->mapLocalizedAttributesNames(
                        $productConcreteTransfer->getLocalizedAttributes(),
                        $productAbstractTransfer->getLocalizedAttributes(),
                    );

                $productConcreteTransfer->setLocalizedAttributes($localizedAttributeTransfers);
            }

            if ($data[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_DESCRIPTION]) {
                $localizedAttributeTransfers = $this->getFactory()
                    ->createProductAttributesMapper()
                    ->mapLocalizedDescriptions(
                        $productConcreteTransfer->getLocalizedAttributes(),
                        $productAbstractTransfer->getLocalizedAttributes(),
                    );
                $productConcreteTransfer->setLocalizedAttributes($localizedAttributeTransfers);
            }

            if ($data[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_IMAGE_SETS]) {
                $concreteProductImageSetsTransfers = $this->mapAbstractProductImageSets(
                    $productAbstractTransfer->getImageSets(),
                    $productConcreteTransfer->getIdProductConcreteOrFail(),
                );

                $productConcreteTransfer->setImageSets($concreteProductImageSetsTransfers);
            }
        }

        $this->getFactory()
            ->getProductFacade()
            ->saveProductConcrete($productConcreteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return array<string>
     */
    protected function getImageSetTabNames(ProductConcreteTransfer $productConcreteTransfer): array
    {
        $localeNamesIndexedByIdLocale = $this->getLocaleNamesIndexedByIdLocale($productConcreteTransfer->getLocalizedAttributes());

        asort($localeNamesIndexedByIdLocale);

        return [0 => static::DEFAULT_LOCALE] + $localeNamesIndexedByIdLocale;
    }

    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @phpstan-return array<int, string>
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @return array<string>
     */
    protected function getLocaleNamesIndexedByIdLocale(ArrayObject $localizedAttributesTransfers): array
    {
        $result = [];

        foreach ($localizedAttributesTransfers as $localizedAttributesTransfer) {
            $idLocale = $localizedAttributesTransfer->getLocaleOrFail()->getIdLocaleOrFail();
            $localeName = $localizedAttributesTransfer->getLocaleOrFail()->getLocaleNameOrFail();

            $result[$idLocale] = $localeName;
        }

        return $result;
    }

    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     *
     * @phpstan-return ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer>
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     * @param int $idProductConcrete
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    protected function mapAbstractProductImageSets(ArrayObject $productImageSetTransfers, int $idProductConcrete): ArrayObject
    {
        $mappedImageSetsTransfers = new ArrayObject();

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $newProductImages = new ArrayObject();
            foreach ($productImageSetTransfer->getProductImages() as $productImage) {
                $newProductImage = new ProductImageTransfer();
                $newProductImage->fromArray($productImage->toArray());
                $newProductImage->setIdProductImage(null);
                $newProductImage->setIdProductImageSetToProductImage(null);
                $newProductImages[] = $newProductImage;
            }

            $newImageSet = new ProductImageSetTransfer();
            $newImageSet->setIdProduct($idProductConcrete)
                ->setLocale($productImageSetTransfer->getLocale())
                ->setName($productImageSetTransfer->getNameOrFail())
                ->setProductImages($newProductImages);

            $newImageSet->setIdProductAbstract(null);
            $newImageSet->setIdProduct($idProductConcrete);

            $mappedImageSetsTransfers[] = $newImageSet;
        }

        return $mappedImageSetsTransfers;
    }
}
