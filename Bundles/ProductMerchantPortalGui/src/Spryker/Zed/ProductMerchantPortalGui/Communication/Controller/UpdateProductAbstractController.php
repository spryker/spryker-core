<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;
use Generated\Shared\Transfer\ProductAbstractResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class UpdateProductAbstractController extends AbstractController
{
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'product-abstract-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idProductAbstract = $this->castId($request->get(static::PARAM_ID_PRODUCT_ABSTRACT));
        $idMerchant = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getIdMerchant();

        $productAbstractTransfer = $this->getFactory()
            ->createProductAbstractFormDataProvider()
            ->findProductAbstract($idProductAbstract, $idMerchant);
        $initialCategoryIds = $productAbstractTransfer->getCategoryIds();

        if (!$productAbstractTransfer) {
            throw new NotFoundHttpException(sprintf(
                'Product abstract is not found for product abstract id %d and merchant id %d.',
                $idProductAbstract,
                $idMerchant
            ));
        }

        $productAbstractForm = $this->getFactory()->createProductAbstractForm(
            $productAbstractTransfer,
            $this->getFactory()->createProductAbstractFormDataProvider()->getOptions()
        );
        $productAbstractForm->handleRequest($request);
        $productAbstractResponseTransfer = new ProductAbstractResponseTransfer();
        $initialData = $this->getDefaultInitialData($request, $productAbstractForm->getName());

        if (!$productAbstractForm->isSubmitted()) {
            return $this->getResponse($productAbstractForm, $productAbstractTransfer, $productAbstractResponseTransfer, $initialData);
        }

        $validationResponseTransfer = $this->getFactory()
            ->getPriceProductFacade()
            ->validatePrices($productAbstractForm->getData()->getPrices());

        if ($productAbstractForm->isValid() && $validationResponseTransfer->getIsSuccess()) {
            $productAbstractTransfer = $productAbstractForm->getData();
            $merchantProductTransfer = (new MerchantProductTransfer())->setProductAbstract($productAbstractTransfer)
                ->setIdMerchant($idMerchant);

            $productAbstractResponseTransfer = $this->getFactory()
                ->getMerchantProductFacade()->updateProductAbstract($merchantProductTransfer);
            $this->updateProductCategories($productAbstractTransfer, $initialCategoryIds);
        }

        $initialData = $this->getFactory()->createPriceProductMapper()->mapValidationResponseTransferToInitialDataErrors(
            $validationResponseTransfer,
            $initialData
        );

        return $this->getResponse($productAbstractForm, $productAbstractTransfer, $productAbstractResponseTransfer, $initialData);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productAbstractForm
     * @phpstan-param array<mixed> $initialData
     *
     * @param \Symfony\Component\Form\FormInterface $productAbstractForm
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractResponseTransfer $productAbstractResponseTransfer
     * @param array $initialData
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productAbstractForm,
        ProductAbstractTransfer $productAbstractTransfer,
        ProductAbstractResponseTransfer $productAbstractResponseTransfer,
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
                    ->getConfiguration($productAbstractTransfer->getIdProductAbstract(), $initialData),
                'productAbstractAttributeTableConfiguration' => $this->getFactory()
                    ->createProductAttributeGuiTableConfigurationProvider()
                    ->getConfiguration($productAbstractTransfer->getAttributes(), $productAbstractTransfer->getLocalizedAttributes()),
                'productCategoryTree' => $this->getFactory()->createProductAbstractFormDataProvider()->getProductCategoryTree(),
            ])->getContent(),
        ];

        if (!$productAbstractForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if ($productAbstractForm->isValid() && $productAbstractResponseTransfer->getIsSuccessful()) {
            $responseData['postActions'] = [
                [
                    'type' => 'close_overlay',
                ],
                [
                    'type' => 'refresh_table',
                ],
            ];
            $responseData['notifications'] = [[
                'type' => 'success',
                'message' => 'The Product is saved.',
            ]];

            return new JsonResponse($responseData);
        }

        if (!$productAbstractForm->isValid()) {
            $responseData['notifications'][] = [
                'type' => 'error',
                'message' => 'Please resolve all errors.',
            ];
        }

        if (!$productAbstractResponseTransfer->getIsSuccessful()) {
            foreach ($productAbstractResponseTransfer->getMessages() as $messageTransfer) {
                $responseData['notifications'][] = [
                    'type' => 'error',
                    'message' => $messageTransfer->getValue(),
                ];
            }
        }

        return new JsonResponse($responseData);
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
     * @phpstan-return array<mixed>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $formName
     *
     * @return array
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
