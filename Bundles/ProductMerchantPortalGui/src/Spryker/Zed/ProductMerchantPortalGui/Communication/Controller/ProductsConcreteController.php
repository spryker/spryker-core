<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class ProductsConcreteController extends AbstractController
{
    protected const PARAM_ACTIVATION_NAME_STATUS = 'activationNameStatus';
    protected const PARAM_ACTIVATION_NAME_VALIDITY = 'activationNameValidity';
    protected const PARAM_PRODUCT_IDS = 'product-ids';

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

        if ($productConcreteBulkForm->isSubmitted() && $productConcreteBulkForm->isValid()) {
            $this->saveConcreteProducts($request, $productConcreteBulkForm, $productConcreteCollectionTransfer);
        }

        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/product_concrete_bulk_form.twig', [
                'productConcreteBulkForm' => $productConcreteBulkForm->createView(),
                'variantsNumber' => $productConcreteCollectionTransfer->getProducts()->count(),
                'activationNameStatus' => static::PARAM_ACTIVATION_NAME_STATUS,
                'activationNameValidity' => static::PARAM_ACTIVATION_NAME_VALIDITY,
            ])->getContent(),
        ];

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
        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransferToSave */
        $productConcreteTransferToSave = $productConcreteBulkForm->getData();

        if (!$request->get(static::PARAM_ACTIVATION_NAME_STATUS) && !$request->get(static::PARAM_ACTIVATION_NAME_VALIDITY)) {
            return;
        }

        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            if ($request->get(static::PARAM_ACTIVATION_NAME_STATUS)) {
                $productConcreteTransfer->setIsActive($productConcreteTransferToSave->getIsActive());
            }

            if ($request->get(static::PARAM_ACTIVATION_NAME_VALIDITY)) {
                $productConcreteTransfer->setValidFrom($productConcreteTransferToSave->getValidFrom());
                $productConcreteTransfer->setValidTo($productConcreteTransferToSave->getValidTo());
            }

            $this->getFactory()->getProductFacade()->saveProductConcrete($productConcreteTransfer);
        }
    }
}
