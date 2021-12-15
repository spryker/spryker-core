<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\MerchantProductNotFoundException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class AddProductConcreteController extends AbstractController
{
    /**
     * @var string
     */
    protected const RESPONSE_KEY_POST_ACTIONS = 'postActions';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_NOTIFICATIONS = 'notifications';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_TYPE = 'type';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_MESSAGE = 'message';

    /**
     * @var string
     */
    protected const RESPONSE_TYPE_REFRESH_TABLE = 'refresh_table';

    /**
     * @var string
     */
    protected const RESPONSE_TYPE_CLOSE_OVERLAY = 'close_overlay';

    /**
     * @var string
     */
    protected const RESPONSE_TYPE_SUCCESS = 'success';

    /**
     * @var string
     */
    protected const RESPONSE_TYPE_ERROR = 'error';

    /**
     * @var string
     */
    protected const RESPONSE_MESSAGE_SUCCESS_PRODUCTS_SAVED = 'Success! %d Concrete Products are saved.';

    /**
     * @var string
     */
    protected const RESPONSE_MESSAGE_SUCCESS_PRODUCT_SAVED = 'Success! %d Concrete Product is saved.';

    /**
     * @var string
     */
    protected const RESPONSE_MESSAGE_ERROR = 'Please resolve all errors.';

    /**
     * @var string
     */
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'product-abstract-id';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\AddProductConcreteForm::FIELD_ID_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const ADD_PRODUCT_CONCRETE_FORM_FIELD_ID_PRODUCT_ABSTRACT = 'idProductAbstract';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\AddProductConcreteForm::FIELD_EXISTING_ATTRIBUTES
     *
     * @var string
     */
    protected const ADD_PRODUCT_CONCRETE_FORM_FIELD_EXISTING_ATTRIBUTES = 'existing_attributes';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\AddProductConcreteForm::FIELD_PRODUCTS
     *
     * @var string
     */
    protected const FIELD_PRODUCTS = 'products';

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

        $idMerchant = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getIdMerchantOrFail();
        $merchantProductTransfer = $this->getFactory()->getMerchantProductFacade()->findMerchantProduct(
            (new MerchantProductCriteriaTransfer())->addIdMerchant($idMerchant)->setIdProductAbstract($idProductAbstract),
        );

        if (!$merchantProductTransfer) {
            throw new MerchantProductNotFoundException($idMerchant, $idProductAbstract);
        }

        $productManagementAttributeTransfers = $this->getFactory()
            ->getProductAttributeFacade()
            ->getUniqueSuperAttributesFromConcreteProducts($merchantProductTransfer->getProducts()->getArrayCopy());

        $attributes = $this->getFactory()
            ->createAttributesDataProvider()
            ->getProductAttributesData($productManagementAttributeTransfers);

        $addProductConcreteForm = $this->getFactory()->createAddProductConcreteForm([
            static::ADD_PRODUCT_CONCRETE_FORM_FIELD_ID_PRODUCT_ABSTRACT => $idProductAbstract,
            static::ADD_PRODUCT_CONCRETE_FORM_FIELD_EXISTING_ATTRIBUTES => $this->getFactory()
                ->getUtilEncodingService()
                ->encodeJson($attributes),
        ]);
        $addProductConcreteForm->handleRequest($request);

        $defaultStoreDefaultLocaleTransfer = $this->getFactory()
            ->createLocaleDataProvider()
            ->getDefaultStoreDefaultLocale();

        if ($addProductConcreteForm->isSubmitted() && $addProductConcreteForm->isValid()) {
            $productConcreteCollectionTransfer = $this->getProductConcreteCollection(
                $addProductConcreteForm,
                $defaultStoreDefaultLocaleTransfer,
            );
            $this->getFactory()->getProductFacade()->createProductConcreteCollection($productConcreteCollectionTransfer);
        }

        return $this->getResponse(
            $addProductConcreteForm,
            $merchantProductTransfer,
            $productConcreteCollectionTransfer ?? new ProductConcreteCollectionTransfer(),
            $defaultStoreDefaultLocaleTransfer,
            $productManagementAttributeTransfers,
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $addProductConcreteForm
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    protected function getProductConcreteCollection(
        FormInterface $addProductConcreteForm,
        LocaleTransfer $localeTransfer
    ): ProductConcreteCollectionTransfer {
        $formData = $addProductConcreteForm->getData();
        $productConcreteCollectionTransfer = $this->getFactory()
            ->createProductFormTransferMapper()
            ->mapAddProductConcreteFormDataToProductConcreteCollectionTransfer(
                $formData,
                new ProductConcreteCollectionTransfer(),
                $localeTransfer,
            );

        $this->getFactory()
            ->createProductStockExpander()
            ->expandProductConcreteTransfersWithDefaultMerchantProductStock($productConcreteCollectionTransfer->getProducts()->getArrayCopy());

        return $productConcreteCollectionTransfer;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $addProductConcreteForm
     *
     * @param \Symfony\Component\Form\FormInterface $addProductConcreteForm
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $defaultStoreDefaultLocaleTransfer
     * @param array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $addProductConcreteForm,
        MerchantProductTransfer $merchantProductTransfer,
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer,
        LocaleTransfer $defaultStoreDefaultLocaleTransfer,
        array $productManagementAttributeTransfers
    ): JsonResponse {
        $responseData = $this->getResponseData(
            $addProductConcreteForm,
            $merchantProductTransfer,
            $defaultStoreDefaultLocaleTransfer,
            $productManagementAttributeTransfers,
        );

        if (!$addProductConcreteForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if ($addProductConcreteForm->isValid()) {
            $responseData = $this->addSuccessResponseDataToResponse($responseData, $productConcreteCollectionTransfer);

            return new JsonResponse($responseData);
        }

        $responseData = $this->addErrorResponseDataToResponse($responseData);

        return new JsonResponse($responseData);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $addProductConcreteForm
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $defaultStoreDefaultLocaleTransfer
     * @param array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     *
     * @return array<mixed>
     */
    protected function getResponseData(
        FormInterface $addProductConcreteForm,
        MerchantProductTransfer $merchantProductTransfer,
        LocaleTransfer $defaultStoreDefaultLocaleTransfer,
        array $productManagementAttributeTransfers
    ): array {
        $attributesDataProvider = $this->getFactory()->createAttributesDataProvider();
        $localizedAttributesExtractor = $this->getFactory()->createLocalizedAttributesExtractor();

        $productAbstractTransfer = $merchantProductTransfer->getProductAbstractOrFail();
        $localizedAttributesTransfer = $localizedAttributesExtractor->extractLocalizedAttributes(
            $productAbstractTransfer->getLocalizedAttributes(),
            $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
        );
        $defaultLocalizedAttributesTransfer = $localizedAttributesExtractor->extractLocalizedAttributes(
            $productAbstractTransfer->getLocalizedAttributes(),
            $defaultStoreDefaultLocaleTransfer,
        );

        return [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/add_product_concrete_form.twig', [
                'form' => $addProductConcreteForm->createView(),
                'productAbstract' => $productAbstractTransfer,
                'attributes' => $attributesDataProvider->getProductAttributesData($productManagementAttributeTransfers),
                'existingProducts' => $attributesDataProvider->getExistingConcreteProductData(
                    $merchantProductTransfer,
                    $productManagementAttributeTransfers,
                    $defaultStoreDefaultLocaleTransfer,
                ),
                'generatedProducts' => $addProductConcreteForm->getData()[static::FIELD_PRODUCTS] ?? [],
                'errors' => $this->getErrors($addProductConcreteForm),
                'attributesErrors' => $this->getFactory()
                    ->createFormErrorsMapper()
                    ->mapAddProductConcreteFormAttributesErrorsToErrorsData($addProductConcreteForm, []),
                'productAbstractDisplayedName' => $localizedAttributesTransfer
                    ? $localizedAttributesTransfer->getName()
                    : $productAbstractTransfer->getName(),
                'productAbstractDefaultName' => $defaultLocalizedAttributesTransfer
                    ? $defaultLocalizedAttributesTransfer->getName()
                    : $productAbstractTransfer->getName(),
            ])->getContent(),
        ];
    }

    /**
     * @param array<mixed> $responseData
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return array<mixed>
     */
    protected function addSuccessResponseDataToResponse(
        array $responseData,
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): array {
        $productsNumber = $productConcreteCollectionTransfer->getProducts()->count();
        $messageTemplate = $productsNumber === 1 ?
            static::RESPONSE_MESSAGE_SUCCESS_PRODUCT_SAVED :
            static::RESPONSE_MESSAGE_SUCCESS_PRODUCTS_SAVED;

        $message = sprintf(
            $this->getFactory()->getTranslatorFacade()->trans($messageTemplate),
            $productsNumber,
        );

        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addActionCloseDrawer()
            ->addActionRefreshTable()
            ->addSuccessNotification($message)
            ->createResponse();

        return array_merge($responseData, $zedUiFormResponseTransfer->toArray());
    }

    /**
     * @param array<mixed> $responseData
     *
     * @return array<mixed>
     */
    protected function addErrorResponseDataToResponse(array $responseData): array
    {
        $message = $this->getFactory()->getTranslatorFacade()->trans(static::RESPONSE_MESSAGE_ERROR);

        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addErrorNotification($message)
            ->createResponse();

        return array_merge($responseData, $zedUiFormResponseTransfer->toArray());
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $addProductConcreteForm
     *
     * @param \Symfony\Component\Form\FormInterface $addProductConcreteForm
     *
     * @return array<mixed>
     */
    protected function getErrors(FormInterface $addProductConcreteForm)
    {
        $errors = [];

        if (!$addProductConcreteForm->isSubmitted() || $addProductConcreteForm->isValid()) {
            return $errors;
        }

        return $this->getFactory()->createFormErrorsMapper()->mapAddProductConcreteFormErrorsToErrorsData(
            $addProductConcreteForm,
            $errors,
        );
    }
}
