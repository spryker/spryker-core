<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

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
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'product-abstract-id';

    protected const RESPONSE_MESSAGE_SUCCESS = 'The Product is saved.';
    protected const RESPONSE_MESSAGE_ERROR = 'Please resolve all errors.';

    protected const RESPONSE_KEY_POST_ACTIONS = 'postActions';
    protected const RESPONSE_KEY_NOTIFICATIONS = 'notifications';
    protected const RESPONSE_KEY_TYPE = 'type';
    protected const RESPONSE_KEY_MESSAGE = 'message';

    protected const RESPONSE_TYPE_REFRESH_DRAWER = 'refresh_drawer';
    protected const RESPONSE_TYPE_REFRESH_TABLE = 'refresh_table';
    protected const RESPONSE_TYPE_CLOSE_OVERLAY = 'close_overlay';
    protected const RESPONSE_TYPE_SUCCESS = 'success';
    protected const RESPONSE_TYPE_ERROR = 'error';

    protected const DEFAULT_INITIAL_DATA = [
        GuiTableEditableInitialDataTransfer::DATA => [],
        GuiTableEditableInitialDataTransfer::ERRORS => [],
    ];

    /**
     * @see \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\AddProductConcreteController::indexAction()
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
        $productAbstractForm = $this->getFactory()->createProductAbstractForm(
            $productAbstractTransfer,
            $this->getFactory()->createProductAbstractFormDataProvider()->getOptions()
        );

        $storedProductAttributes = $productAbstractTransfer->getAttributes();

        $productAbstractForm->handleRequest($request);

        $priceTableInitialData = $this->getDefaultInitialData(
            PriceProductTableViewTransfer::PRICES,
            $request->get($productAbstractForm->getName())
        );
        $attributesTableInitialData = $this->getDefaultInitialData(
            ProductAbstractTransfer::ATTRIBUTES,
            $request->get($productAbstractForm->getName())
        );

        if ($productAbstractForm->isSubmitted()) {
            $productAbstractTransfer->setAttributes($storedProductAttributes);

            return $this->executeProductAbstractFormSubmission(
                $productAbstractForm,
                $productAbstractTransfer,
                $idMerchant,
                $priceTableInitialData,
                $attributesTableInitialData,
                $initialCategoryIds
            );
        }

        return $this->getResponse(
            $productAbstractForm,
            $productAbstractTransfer,
            new ValidationResponseTransfer(),
            $priceTableInitialData,
            $attributesTableInitialData
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productAbstractForm
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param int $idMerchant
     * @param mixed[] $priceInitialData
     * @param array $attributesInitialData
     * @param int[] $initialCategoryIds
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
        $pricesValidationResponseTransfer = $this->getFactory()
            ->getPriceProductFacade()
            ->validatePrices($productAbstractForm->getData()->getPrices());
        $merchantProductValidationResponseTransfer = new ValidationResponseTransfer();

        $isValid = $productAbstractForm->isValid();

        if ($isValid && $pricesValidationResponseTransfer->getIsSuccess()) {
            $productAbstractTransfer = $productAbstractForm->getData();
            $merchantProductTransfer = (new MerchantProductTransfer())
                ->setProductAbstract($productAbstractTransfer)
                ->setIdMerchant($idMerchant);

            $merchantProductValidationResponseTransfer = $this->getFactory()
                ->getMerchantProductFacade()
                ->validateMerchantProduct($merchantProductTransfer);

            $productAbstractTransfer = $this->getFactory()
                ->createProductAbstractMapper()
                ->mapAttributesDataToProductAbstractTransfer($attributesInitialData, $productAbstractTransfer);

            $attributesInitialData = [];

            if ($merchantProductValidationResponseTransfer->getIsSuccess()) {
                $this->getFactory()->getProductFacade()->saveProductAbstract($productAbstractTransfer);
            }

            $this->updateProductCategories($productAbstractTransfer, $initialCategoryIds);
        } else {
            $errors = $productAbstractForm->getErrors(true, false);

            $attributesInitialData = $this->getFactory()
                ->createProductAttributesMapper()
                ->mapErrorsToAttributesData($errors, $attributesInitialData);
        }

        $priceInitialData = $this->getFactory()
            ->createPriceProductMapper()
            ->mapValidationResponseTransferToInitialDataErrors($pricesValidationResponseTransfer, $priceInitialData);

        return $this->getResponse(
            $productAbstractForm,
            $productAbstractTransfer,
            $merchantProductValidationResponseTransfer,
            $priceInitialData,
            $attributesInitialData
        );
    }

    /**
     * @phpstan-param array<int> $initialCategoryIds
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param int[] $initialCategoryIds
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
                [$productAbstractTransfer->getIdProductAbstractOrFail()]
            );
        }

        foreach ($categoryIdsToRemove as $idCategory) {
            $productCategoryFacade->removeProductCategoryMappings(
                $idCategory,
                [$productAbstractTransfer->getIdProductAbstractOrFail()]
            );
        }
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productAbstractForm
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     * @param mixed[] $priceInitialData
     * @param mixed[] $attributesInitialData
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productAbstractForm,
        ProductAbstractTransfer $productAbstractTransfer,
        ValidationResponseTransfer $validationResponseTransfer,
        array $priceInitialData,
        array $attributesInitialData
    ): JsonResponse {
        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        $localizedAttributesTransfer = $this->getFactory()
            ->createLocalizedAttributesExtractor()
            ->extractLocalizedAttributes(
                $productAbstractTransfer->getLocalizedAttributes(),
                $localeTransfer
            );

        $imageSetsGroupedByLocale = [];
        $imageSetMetaData = new SplObjectStorage();
        $imageSetTabNames = $this->getImageSetTabNames($productAbstractTransfer);

        foreach ($productAbstractTransfer->getImageSets() as $originalIndex => $imageSet) {
            $idLocale = 0;

            if ($imageSet->getLocale() !== null) {
                $idLocale = $imageSet->getLocale()->getIdLocaleOrFail();
            }

            $imageSetsGroupedByLocale[$idLocale][] = $imageSet;
            $imageSetMetaData[$imageSet] = [
                'originalIndex' => $originalIndex,
            ];
        }

        $responseData = [
            'form' => $this->renderView(
                '@ProductMerchantPortalGui/Partials/product_abstract_form.twig',
                [
                    'productAbstract' => $productAbstractTransfer,
                    'imageSetTabNames' => $imageSetTabNames,
                    'imageSetsGroupedByLocale' => $imageSetsGroupedByLocale,
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
                ]
            )->getContent(),
        ];

        if (!$productAbstractForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if ($productAbstractForm->isValid() && $validationResponseTransfer->getIsSuccess()) {
            $responseData = $this->addSuccessResponseDataToResponse($responseData);

            return new JsonResponse($responseData);
        }

        if (!$productAbstractForm->isValid()) {
            $responseData = $this->addErrorResponseDataToResponse($responseData);
        }

        if (!$validationResponseTransfer->getIsSuccess()) {
            $responseData = $this->addValidationResponseMessagesToResponse($responseData, $validationResponseTransfer);
        }

        return new JsonResponse($responseData);
    }

    /**
     * @param mixed[] $responseData
     *
     * @return mixed[]
     */
    protected function addSuccessResponseDataToResponse(array $responseData): array
    {
        $responseData[static::RESPONSE_KEY_POST_ACTIONS] = [
            [
                static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_REFRESH_DRAWER,
            ],
        ];
        $responseData[static::RESPONSE_KEY_NOTIFICATIONS] = [
            [
                static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_SUCCESS,
                static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_SUCCESS,
            ],
        ];

        return $responseData;
    }

    /**
     * @param mixed[] $responseData
     *
     * @return mixed[]
     */
    protected function addErrorResponseDataToResponse(array $responseData): array
    {
        $responseData[static::RESPONSE_KEY_NOTIFICATIONS][] = [
            static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_ERROR,
            static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_ERROR,
        ];

        return $responseData;
    }

    /**
     * @param mixed[] $responseData
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return mixed[]
     */
    protected function addValidationResponseMessagesToResponse(
        array $responseData,
        ValidationResponseTransfer $validationResponseTransfer
    ): array {
        foreach ($validationResponseTransfer->getValidationErrors() as $validationErrorTransfer) {
            $responseData[static::RESPONSE_KEY_NOTIFICATIONS][] = [
                static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_ERROR,
                static::RESPONSE_KEY_MESSAGE => $validationErrorTransfer->getMessage(),
            ];
        }

        return $responseData;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableDataAction(Request $request): Response
    {
        $idProductAbstract = $this->castId($request->get(PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT));

        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createPriceProductAbstractTableDataProvider($idProductAbstract),
            $this->getFactory()->createPriceProductAbstractGuiTableConfigurationProvider()->getConfiguration(
                $idProductAbstract
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return string[]
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
