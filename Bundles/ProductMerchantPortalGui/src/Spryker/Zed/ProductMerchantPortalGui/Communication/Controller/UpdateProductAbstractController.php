<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\MerchantProductNotFoundException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class UpdateProductAbstractController extends AbstractController
{
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'product-abstract-id';

    protected const RESPONSE_MESSAGE_SUCCESS = 'The Product is saved.';
    protected const RESPONSE_MESSAGE_ERROR = 'Please resolve all errors.';

    protected const RESPONSE_KEY_POST_ACTIONS = 'postActions';
    protected const RESPONSE_KEY_NOTIFICATIONS = 'notifications';
    protected const RESPONSE_KEY_TYPE = 'type';
    protected const RESPONSE_KEY_MESSAGE = 'message';

    protected const RESPONSE_TYPE_REFRESH_TABLE = 'refresh_table';
    protected const RESPONSE_TYPE_CLOSE_OVERLAY = 'close_overlay';
    protected const RESPONSE_TYPE_SUCCESS = 'success';
    protected const RESPONSE_TYPE_ERROR = 'error';

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
        $productAbstractForm->handleRequest($request);
        $initialData = $this->getDefaultInitialData($request, $productAbstractForm->getName());

        if ($productAbstractForm->isSubmitted()) {
            return $this->executeProductAbstractFormSubmission(
                $productAbstractForm,
                $productAbstractTransfer,
                $idMerchant,
                $initialData,
                $initialCategoryIds
            );
        }

        return $this->getResponse($productAbstractForm, $productAbstractTransfer, new ValidationResponseTransfer(), $initialData);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productAbstractForm
     *
     * @param \Symfony\Component\Form\FormInterface $productAbstractForm
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param int $idMerchant
     * @param mixed[] $initialData
     * @param int[] $initialCategoryIds
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function executeProductAbstractFormSubmission(
        FormInterface $productAbstractForm,
        ProductAbstractTransfer $productAbstractTransfer,
        int $idMerchant,
        array $initialData,
        array $initialCategoryIds
    ): JsonResponse {
        $pricesValidationResponseTransfer = $this->getFactory()
            ->getPriceProductFacade()
            ->validatePrices($productAbstractForm->getData()->getPrices());
        $merchantProductValidationResponseTransfer = new ValidationResponseTransfer();

        if ($productAbstractForm->isValid() && $pricesValidationResponseTransfer->getIsSuccess()) {
            $productAbstractTransfer = $productAbstractForm->getData();
            $merchantProductTransfer = (new MerchantProductTransfer())->setProductAbstract($productAbstractTransfer)
                ->setIdMerchant($idMerchant);

            $merchantProductValidationResponseTransfer = $this->getFactory()
                ->getMerchantProductFacade()->validateMerchantProduct($merchantProductTransfer);

            if ($merchantProductValidationResponseTransfer->getIsSuccess()) {
                $this->getFactory()->getProductFacade()->saveProductAbstract($productAbstractTransfer);
            }

            $this->updateProductCategories($productAbstractTransfer, $initialCategoryIds);
        }

        $initialData = $this->getFactory()->createPriceProductMapper()->mapValidationResponseTransferToInitialDataErrors(
            $pricesValidationResponseTransfer,
            $initialData
        );

        return $this->getResponse($productAbstractForm, $productAbstractTransfer, $merchantProductValidationResponseTransfer, $initialData);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productAbstractForm
     *
     * @param \Symfony\Component\Form\FormInterface $productAbstractForm
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     * @param mixed[] $initialData
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productAbstractForm,
        ProductAbstractTransfer $productAbstractTransfer,
        ValidationResponseTransfer $validationResponseTransfer,
        array $initialData
    ): JsonResponse {
        $localeTransfer = $this->getFactory()->getLocaleFacade()->getCurrentLocale();
        $productAbstractName = $this->getFactory()
            ->createProductAbstractNameBuilder()
            ->buildProductAbstractName($productAbstractTransfer, $localeTransfer);

        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/product_abstract_form.twig', [
                'productAbstract' => $productAbstractTransfer,
                'productAbstractName' => $productAbstractName,
                'form' => $productAbstractForm->createView(),
                'priceProductAbstractTableConfiguration' => $this->getFactory()
                    ->createPriceProductAbstractGuiTableConfigurationProvider()
                    ->getConfiguration($productAbstractTransfer->getIdProductAbstractOrFail(), $initialData),
                'productAbstractAttributeTableConfiguration' => $this->getFactory()
                    ->createProductAttributeGuiTableConfigurationProvider()
                    ->getConfiguration($productAbstractTransfer->getAttributes(), $productAbstractTransfer->getLocalizedAttributes()),
                'productCategoryTree' => $this->getFactory()->createProductAbstractFormDataProvider()->getProductCategoryTree(),
            ])->getContent(),
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
                static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_CLOSE_OVERLAY,
            ],
            [
                static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_REFRESH_TABLE,
            ],
        ];
        $responseData[static::RESPONSE_KEY_NOTIFICATIONS] = [[
            static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_SUCCESS,
            static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_SUCCESS,
        ]];

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
        $idProductAbstract = $this->castId($request->get(PriceProductAbstractTableViewTransfer::ID_PRODUCT_ABSTRACT));

        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createPriceProductAbstractTableDataProvider($idProductAbstract),
            $this->getFactory()->createPriceProductAbstractGuiTableConfigurationProvider()->getConfiguration($idProductAbstract)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $formName
     *
     * @return mixed[]
     */
    protected function getDefaultInitialData(Request $request, string $formName): array
    {
        $requestTableData = $request->get($formName);
        $requestTableData = $this->getFactory()->getUtilEncodingService()->decodeJson(
            $requestTableData[PriceProductAbstractTableViewTransfer::PRICES],
            true
        );

        if (!$requestTableData) {
            return [
                GuiTableEditableInitialDataTransfer::DATA => [],
                GuiTableEditableInitialDataTransfer::ERRORS => [],
            ];
        }

        return [
            GuiTableEditableInitialDataTransfer::DATA => $requestTableData,
            GuiTableEditableInitialDataTransfer::ERRORS => [],
        ];
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
            $productCategoryFacade->createProductCategoryMappings($idCategory, [$productAbstractTransfer->getIdProductAbstractOrFail()]);
        }

        foreach ($categoryIdsToRemove as $idCategory) {
            $productCategoryFacade->removeProductCategoryMappings($idCategory, [$productAbstractTransfer->getIdProductAbstractOrFail()]);
        }
    }
}
