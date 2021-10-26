<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use SplObjectStorage;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\MerchantProductNotFoundException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class UpdateProductAbstractController extends AbstractUpdateProductController
{
    /**
     * @var string
     */
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'product-abstract-id';

    /**
     * @var array
     */
    protected const DEFAULT_INITIAL_DATA = [
        GuiTableEditableInitialDataTransfer::DATA => [],
        GuiTableEditableInitialDataTransfer::ERRORS => [],
    ];

    /**
     * @see \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\AddProductConcreteController::indexAction()
     *
     * @var string
     */
    protected const URL_ADD_PRODUCT_CONCRETE = '/product-merchant-portal-gui/add-product-concrete';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\MerchantProductNotFoundException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idProductAbstract = $this->castId($request->get(static::PARAM_ID_PRODUCT_ABSTRACT));

        $idMerchant = $this->getFactory()
            ->getMerchantUserFacade()
            ->getCurrentMerchantUser()
            ->getIdMerchantOrFail();

        $productAbstractTransfer = $this->getFactory()
            ->createProductAbstractFormDataProvider()
            ->findProductAbstract($idProductAbstract, $idMerchant);

        if (!$productAbstractTransfer) {
            throw new MerchantProductNotFoundException($idProductAbstract, $idMerchant);
        }

        $initialCategoryIds = $productAbstractTransfer->getCategoryIds();
        $productAbstractForm = $this->getFactory()
            ->createProductAbstractForm(
                $productAbstractTransfer,
                $this->getFactory()
                    ->createProductAbstractFormDataProvider()
                    ->getOptions(),
            );

        $storedProductAttributes = $productAbstractTransfer->getAttributes();

        $productAbstractForm->handleRequest($request);

        $priceTableInitialData = $this->getDefaultInitialData(
            PriceProductTableViewTransfer::PRICES,
            $request->get($productAbstractForm->getName()),
        );
        $attributesTableInitialData = $this->getDefaultInitialData(
            ProductAbstractTransfer::ATTRIBUTES,
            $request->get($productAbstractForm->getName()),
        );

        if ($productAbstractForm->isSubmitted()) {
            $productAbstractTransfer->setAttributes($storedProductAttributes);

            return $this->executeProductAbstractFormSubmission(
                $productAbstractForm,
                $productAbstractTransfer,
                $idMerchant,
                $priceTableInitialData,
                $attributesTableInitialData,
                $initialCategoryIds,
            );
        }

        return $this->getResponse(
            $productAbstractForm,
            $productAbstractTransfer,
            new ValidationResponseTransfer(),
            $priceTableInitialData,
            $attributesTableInitialData,
            [],
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productAbstractForm
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param int $idMerchant
     * @param array<mixed> $priceInitialData
     * @param array $attributesInitialData
     * @param array<int> $initialCategoryIds
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function executeProductAbstractFormSubmission(
        FormInterface $productAbstractForm,
        ProductAbstractTransfer $productAbstractTransfer,
        int $idMerchant,
        array $priceInitialData,
        array $attributesInitialData,
        array $initialCategoryIds
    ): JsonResponse {
        $imageSetsErrors = [];

        $priceProductTransfers = $productAbstractForm->getData()->getPrices();
        $pricesValidationResponseTransfer = $this->getFactory()
            ->getPriceProductFacade()
            ->validatePrices($priceProductTransfers);

        $merchantProductValidationResponseTransfer = (new ValidationResponseTransfer())->setIsSuccess(true);

        $isValid = $productAbstractForm->isValid();

        if ($isValid && $pricesValidationResponseTransfer->getIsSuccess()) {
            /** @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer */
            $productAbstractTransfer = $productAbstractForm->getData();
            $merchantProductTransfer = (new MerchantProductTransfer())
                ->setProductAbstract($productAbstractTransfer)
                ->setIdMerchant($idMerchant);

            $merchantProductValidationResponseTransfer = $this->getFactory()
                ->getMerchantProductFacade()
                ->validateMerchantProduct($merchantProductTransfer);

            $productAttributes = $this->getFactory()
                ->createProductAttributesMapper()
                ->mapAttributesDataToProductAttributes(
                    $attributesInitialData,
                    $productAbstractTransfer->getAttributes(),
                );
            $productLocalizedAttributes = $this->getFactory()
                ->createProductAttributesMapper()
                ->mapAttributesDataToLocalizedAttributesTransfers(
                    $attributesInitialData,
                    $productAbstractTransfer->getLocalizedAttributes(),
                );

            $productAbstractTransfer->setAttributes($productAttributes)->setLocalizedAttributes($productLocalizedAttributes);

            $attributesInitialData = [];

            if ($merchantProductValidationResponseTransfer->getIsSuccess()) {
                $this->getFactory()
                    ->getProductFacade()
                    ->saveProductAbstract($productAbstractTransfer);
            }

            $this->updateProductCategories($productAbstractTransfer, $initialCategoryIds);
        } else {
            $imageSetsErrors = $this->getFactory()
                ->createImageSetMapper()
                ->mapErrorsToImageSetValidationData(
                    $productAbstractForm->getErrors(true, true),
                );

            $attributesInitialData = $this->getFactory()
                ->createProductAttributesMapper()
                ->mapErrorsToAttributesData($productAbstractForm->getErrors(true, false), $attributesInitialData);
        }

        $priceInitialData = $this->getFactory()
            ->createPriceProductValidationMapper()
            ->mapValidationResponseTransferToInitialData(
                $pricesValidationResponseTransfer,
                $priceProductTransfers,
                $priceInitialData,
            );

        $merchantProductValidationResponseTransfer->setIsSuccess(
            $pricesValidationResponseTransfer->getIsSuccessOrFail()
            && $merchantProductValidationResponseTransfer->getIsSuccessOrFail(),
        );

        return $this->getResponse(
            $productAbstractForm,
            $productAbstractTransfer,
            $merchantProductValidationResponseTransfer,
            $priceInitialData,
            $attributesInitialData,
            $imageSetsErrors,
        );
    }

    /**
     * @phpstan-param array<int> $initialCategoryIds
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array<int> $initialCategoryIds
     *
     * @return void
     */
    protected function updateProductCategories(
        ProductAbstractTransfer $productAbstractTransfer,
        array $initialCategoryIds
    ): void {
        $categoryIdsToAdd = array_diff($productAbstractTransfer->getCategoryIds(), $initialCategoryIds);
        $categoryIdsToRemove = array_diff($initialCategoryIds, $productAbstractTransfer->getCategoryIds());
        $productCategoryFacade = $this->getFactory()->getProductCategoryFacade();

        foreach ($categoryIdsToAdd as $idCategory) {
            $productCategoryFacade->createProductCategoryMappings(
                $idCategory,
                [$productAbstractTransfer->getIdProductAbstractOrFail()],
            );
        }

        foreach ($categoryIdsToRemove as $idCategory) {
            $productCategoryFacade->removeProductCategoryMappings(
                $idCategory,
                [$productAbstractTransfer->getIdProductAbstractOrFail()],
            );
        }
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productAbstractForm
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     * @param array $priceInitialData
     * @param array $attributesInitialData
     * @param array $imageSetsErrors
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productAbstractForm,
        ProductAbstractTransfer $productAbstractTransfer,
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
                $productAbstractTransfer->getLocalizedAttributes(),
                $localeTransfer,
            );

        $imageSetTabNames = $this->getImageSetTabNames($productAbstractTransfer);
        $imageSetsGroupedByIdLocale = $this->getImageSetsGroupedByIdLocale($productAbstractTransfer->getImageSets());
        $imageSetMetaData = $this->getImageSetMetaDataGroupedByImageSet($productAbstractTransfer->getImageSets(), $imageSetsErrors);

        $responseData = [
            'form' => $this->renderView(
                '@ProductMerchantPortalGui/Partials/product_abstract_form.twig',
                [
                    'productAbstract' => $productAbstractTransfer,
                    'imageSetTabNames' => $imageSetTabNames,
                    'imageSetsGroupedByIdLocale' => $imageSetsGroupedByIdLocale,
                    'imageSetMetaData' => $imageSetMetaData,
                    'productAbstractName' => $localizedAttributesTransfer ? $localizedAttributesTransfer->getName() : $productAbstractTransfer->getName(),
                    'form' => $productAbstractForm->createView(),
                    'priceProductAbstractTableConfiguration' => $this->getFactory()
                        ->createPriceProductAbstractGuiTableConfigurationProvider()
                        ->getConfiguration($productAbstractTransfer->getIdProductAbstractOrFail(), $priceInitialData),
                    'productAbstractAttributeTableConfiguration' => $this->getFactory()
                        ->createProductAbstractAttributeGuiTableConfigurationProvider()
                        ->getConfiguration($productAbstractTransfer->getIdProductAbstractOrFail(), $attributesInitialData),
                    'productConcreteTableConfiguration' => $this->getFactory()
                        ->createProductGuiTableConfigurationProvider()
                        ->getConfiguration($productAbstractTransfer->getIdProductAbstractOrFail()),
                    'productCategoryTree' => $this->getFactory()
                        ->createProductAbstractFormDataProvider()
                        ->getProductCategoryTree(),
                    'urlAddProductConcrete' => static::URL_ADD_PRODUCT_CONCRETE,
                ],
            )->getContent(),
        ];

        if (!$productAbstractForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if ($productAbstractForm->isValid() && $validationResponseTransfer->getIsSuccess()) {
            $responseData = $this->addSuccessResponseDataToResponse($responseData);

            return new JsonResponse($responseData);
        }

        return new JsonResponse(
            $this->addErrorResponseDataToResponse(
                $productAbstractForm,
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableDataAction(Request $request): Response
    {
        $idProductAbstract = $this->castId($request->get(PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT));

        return $this->getFactory()
            ->getGuiTableHttpDataRequestExecutor()
            ->execute(
                $request,
                $this->getFactory()
                    ->createPriceProductAbstractTableDataProvider($idProductAbstract),
                $this->getFactory()
                    ->createPriceProductAbstractGuiTableConfigurationProvider()
                    ->getConfiguration(
                        $idProductAbstract,
                    ),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array<string>
     */
    protected function getImageSetTabNames(ProductAbstractTransfer $productAbstractTransfer)
    {
        $result = [];

        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $idLocale = $localizedAttribute->getLocaleOrFail()->getIdLocaleOrFail();
            $localeName = $localizedAttribute->getLocaleOrFail()->getLocaleNameOrFail();
            $result[$idLocale] = $localeName;
        }

        asort($result);

        return [0 => 'Default'] + $result;
    }
}
