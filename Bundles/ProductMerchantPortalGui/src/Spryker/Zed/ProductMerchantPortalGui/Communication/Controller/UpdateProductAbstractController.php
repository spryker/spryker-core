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
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_DRAFT
     *
     * @var string
     */
    protected const STATUS_DRAFT = 'draft';

    /**
     * @see \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\AddProductConcreteController::indexAction()
     *
     * @var string
     */
    protected const URL_ADD_PRODUCT_CONCRETE = '/product-merchant-portal-gui/add-product-concrete';

    /**
     * @var string
     */
    protected const ID_TABLE_PRODUCT_CONCRETE = 'product-concrete-table';

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

        $merchantProductTransfer = $this->getFactory()
            ->createProductAbstractFormDataProvider()
            ->findMerchantProduct($idProductAbstract, $idMerchant);

        if (!$merchantProductTransfer) {
            throw new MerchantProductNotFoundException($idProductAbstract, $idMerchant);
        }

        $productAbstractTransfer = $merchantProductTransfer->getProductAbstractOrFail();
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

        $productManagementAttributeTransfers = $this->getFactory()
            ->getProductAttributeFacade()
            ->getUniqueSuperAttributesFromConcreteProducts($merchantProductTransfer->getProducts()->getArrayCopy());

        return $this->getResponse(
            $productAbstractForm,
            $productAbstractTransfer,
            new ValidationResponseTransfer(),
            $priceTableInitialData,
            $attributesTableInitialData,
            [],
            $productManagementAttributeTransfers,
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface<mixed> $productAbstractForm
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param int $idMerchant
     * @param array<string, array<string, mixed>> $priceInitialData
     * @param array<string, array<string, mixed>> $attributesInitialData
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

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers */
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
            /** @var \Symfony\Component\Form\FormErrorIterator<\Symfony\Component\Form\FormError> $formErrors */
            $formErrors = $productAbstractForm->getErrors(true, true);
            $imageSetsErrors = $this->getFactory()
                ->createImageSetMapper()
                ->mapErrorsToImageSetValidationData(
                    $formErrors,
                );

            /** @var \Symfony\Component\Form\FormErrorIterator<\Symfony\Component\Form\FormError> $nestedFormErrors */
            $nestedFormErrors = $productAbstractForm->getErrors(true, false);
            $attributesInitialData = $this->getFactory()
                ->createProductAttributesMapper()
                ->mapErrorsToAttributesData($nestedFormErrors, $attributesInitialData);
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
     * @param \Symfony\Component\Form\FormInterface<mixed> $productAbstractForm
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     * @param array<string, array<string, mixed>> $priceInitialData
     * @param array<string, array<string, mixed>> $attributesInitialData
     * @param array<array<string>> $imageSetsErrors
     * @param array<string, \Generated\Shared\Transfer\ProductManagementAttributeTransfer>|null $productManagementAttributeTransfers
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productAbstractForm,
        ProductAbstractTransfer $productAbstractTransfer,
        ValidationResponseTransfer $validationResponseTransfer,
        array $priceInitialData,
        array $attributesInitialData,
        array $imageSetsErrors,
        ?array $productManagementAttributeTransfers = []
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
        $imageSetMetaDataGroupedByHash = $this->getImageSetMetaDataGroupedByImageSetHash($productAbstractTransfer->getImageSets(), $imageSetsErrors);

        $responseData = [
            'form' => $this->renderView(
                '@ProductMerchantPortalGui/Partials/product_abstract_form.twig',
                [
                    'productAbstract' => $productAbstractTransfer,
                    'imageSetTabNames' => $imageSetTabNames,
                    'imageSetsGroupedByIdLocale' => $imageSetsGroupedByIdLocale,
                    'imageSetMetaData' => $imageSetMetaData, // @deprecated Use `imageSetMetaDataGroupedByHash` instead.
                    'imageSetMetaDataGroupedByHash' => $imageSetMetaDataGroupedByHash,
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
                    'applicableUpdateApprovalStatuses' => $this->getFactory()
                        ->createApplicableApprovalStatusReader()
                        ->getApplicableUpdateApprovalStatuses($productAbstractTransfer->getApprovalStatus() ?? static::STATUS_DRAFT),
                    'superAttributes' => $productManagementAttributeTransfers,
                    'idTableProductConcrete' => static::ID_TABLE_PRODUCT_CONCRETE,
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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $imageSets
     *
     * @return array<int, array<string, \Generated\Shared\Transfer\ProductImageSetTransfer>>
     */
    protected function getImageSetsGroupedByIdLocale(ArrayObject $imageSets): array
    {
        $imageSetsGroupedByIdLocale = [];

        foreach ($imageSets as $imageSet) {
            $idLocale = $imageSet->getLocale() ? $imageSet->getLocaleOrFail()->getIdLocaleOrFail() : 0;
            $imageSetsGroupedByIdLocale[$idLocale][spl_object_hash($imageSet)] = $imageSet;
        }

        return $imageSetsGroupedByIdLocale;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $imageSets
     * @param array<int, mixed> $imageSetsErrors
     *
     * @return \SplObjectStorage<object, mixed>
     */
    protected function getImageSetMetaDataGroupedByImageSet(ArrayObject $imageSets, array $imageSetsErrors): SplObjectStorage
    {
        $imageSetMetaData = new SplObjectStorage();
        foreach ($imageSets as $originalIndex => $imageSet) {
            $imageSetMetaData->attach($imageSet, [
                'originalIndex' => $originalIndex,
                'errors' => $imageSetsErrors[$originalIndex] ?? [],
            ]);
        }

        return $imageSetMetaData;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $imageSets
     * @param array<int, mixed> $imageSetsErrors
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getImageSetMetaDataGroupedByImageSetHash(ArrayObject $imageSets, array $imageSetsErrors): array
    {
        $imageSetMetaData = [];
        foreach ($imageSets as $originalIndex => $imageSet) {
            $imageSetMetaData[spl_object_hash($imageSet)] = [
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
    protected function getImageSetTabNames(ProductAbstractTransfer $productAbstractTransfer): array
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
