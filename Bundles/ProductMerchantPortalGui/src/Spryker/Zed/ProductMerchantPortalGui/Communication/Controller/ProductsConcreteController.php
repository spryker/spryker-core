<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\ProductConcreteNotFoundException;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class ProductsConcreteController extends ProductMerchantPortalAbstractController
{
    protected const PARAM_ACTIVATION_NAME_STATUS = 'activationNameStatus';
    protected const PARAM_ACTIVATION_NAME_VALIDITY = 'activationNameValidity';
    protected const PARAM_PRODUCT_IDS = 'product-ids';
    protected const PARAM_PRODUCT_ID = 'product-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableDataAction(Request $request): Response
    {
        $idProductAbstract = $this->castId($request->get(ProductConcreteTransfer::FK_PRODUCT_ABSTRACT));

        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createProductTableDataProvider($idProductAbstract),
            $this->getFactory()->createProductGuiTableConfigurationProvider()->getConfiguration($idProductAbstract)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function bulkEditAction(Request $request): JsonResponse
    {
        $productIds = array_map(function ($value) {
            return (int)$value;
        }, explode(',', trim($request->get(static::PARAM_PRODUCT_IDS, []), '[]')));
        $idMerchant = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getIdMerchant();
        $productConcreteCollectionTransfer = $this->getFactory()->getMerchantProductFacade()->getProductConcreteCollection(
            (new MerchantProductCriteriaTransfer())->setIdMerchant($idMerchant)->setProductConcreteIds($productIds)
        );
        $productConcreteBulkForm = $this->getFactory()->createProductConcreteBulkForm();
        $productConcreteBulkForm->handleRequest($request);

        $responseData = [];

        if ($productConcreteBulkForm->isSubmitted() && $productConcreteBulkForm->isValid()) {
            $this->saveConcreteProducts($request, $productConcreteBulkForm, $productConcreteCollectionTransfer);

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
                'message' => sprintf('%s Variants are updated', $productConcreteCollectionTransfer->getProducts()->count()),
            ]];
        }

        $responseData['form'] = $this->renderView('@ProductMerchantPortalGui/Partials/product_concrete_bulk_form.twig', [
            'productConcreteBulkForm' => $productConcreteBulkForm->createView(),
            'variantsNumber' => $productConcreteCollectionTransfer->getProducts()->count(),
            'activationNameStatus' => static::PARAM_ACTIVATION_NAME_STATUS,
            'activationNameValidity' => static::PARAM_ACTIVATION_NAME_VALIDITY,
        ])->getContent();

        return new JsonResponse($responseData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\ProductConcreteNotFoundException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request): JsonResponse
    {
        $idProduct = $this->castId($request->get(static::PARAM_PRODUCT_ID));
        $formData = $this->getFactory()->createProductConcreteEditFormDataProvider()->getData($idProduct);
        $productConcreteTransfer = $formData[ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE];

        if (!$formData[ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE]) {
            throw new ProductConcreteNotFoundException($idProduct);
        }

        $productConcreteEditForm = $this->getFactory()->createProductConcreteEditForm($formData);
        $productConcreteEditForm->handleRequest($request);

        if ($productConcreteEditForm->isSubmitted() && $productConcreteEditForm->isValid()) {
            $this->getFactory()->getProductFacade()->saveProductConcrete(
                $formData[ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE]
            );
        }

        return $this->getEditResponse($productConcreteEditForm, $productConcreteTransfer);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productConcreteEditForm
     *
     * @param \Symfony\Component\Form\FormInterface $productConcreteEditForm
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getEditResponse(
        FormInterface $productConcreteEditForm,
        ProductConcreteTransfer $productConcreteTransfer
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

        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/product_concrete_form.twig', [
                'form' => $productConcreteEditForm->createView(),
                'productConcrete' => $productConcreteTransfer,
                'productConcreteName' => $localizedAttributesTransfer ? $localizedAttributesTransfer->getName() : $productConcreteTransfer->getName(),
                'productAttributeTableConfiguration' => $this->getFactory()
                    ->createProductConcreteAttributeGuiTableConfigurationProvider()
                    ->getConfiguration($productConcreteTransfer->getAttributes(), array_keys($superAttributeNames), $productConcreteTransfer->getLocalizedAttributes()),
                'superAttributeNames' => $superAttributeNames,
            ])->getContent(),
        ];

        if (!$productConcreteEditForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if ($productConcreteEditForm->isValid()) {
            $responseData = $this->addSuccessResponseDataToResponse($responseData);

            return new JsonResponse($responseData);
        }

        $responseData = $this->addErrorResponseDataToResponse($responseData);

        return new JsonResponse($responseData);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productConcreteBulkForm
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $productConcreteBulkForm
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return void
     */
    protected function saveConcreteProducts(
        Request $request,
        FormInterface $productConcreteBulkForm,
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void {
        if (!$request->get(static::PARAM_ACTIVATION_NAME_STATUS) && !$request->get(static::PARAM_ACTIVATION_NAME_VALIDITY)) {
            return;
        }

        $formData = $productConcreteBulkForm->getData();

        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $idProductConcrete = $productConcreteTransfer->getIdProductConcreteOrFail();

            if ($request->get(static::PARAM_ACTIVATION_NAME_STATUS)) {
                $formData[ProductConcreteTransfer::IS_ACTIVE]
                    ? $this->getFactory()->getProductFacade()->activateProductConcrete($idProductConcrete)
                    : $this->getFactory()->getProductFacade()->deactivateProductConcrete($idProductConcrete);
            }

            if ($request->get(static::PARAM_ACTIVATION_NAME_VALIDITY)) {
                $this->getFactory()->getProductValidityFacade()->saveProductValidity(
                    (new ProductConcreteTransfer())
                        ->setIdProductConcrete($idProductConcrete)
                        ->setValidFrom($formData[ProductConcreteTransfer::VALID_FROM])
                        ->setValidTo($formData[ProductConcreteTransfer::VALID_TO])
                );
            }
        }
    }
}
