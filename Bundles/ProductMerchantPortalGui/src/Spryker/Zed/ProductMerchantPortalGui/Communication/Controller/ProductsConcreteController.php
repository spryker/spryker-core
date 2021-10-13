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
    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = '%s Variants are updated';

    /**
     * @var string
     */
    protected const PARAM_ACTIVATION_NAME_STATUS = 'activationNameStatus';
    /**
     * @var string
     */
    protected const PARAM_ACTIVATION_NAME_VALIDITY = 'activationNameValidity';
    /**
     * @var string
     */
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

        $responseData = [];

        if ($productConcreteBulkForm->isSubmitted() && $productConcreteBulkForm->isValid()) {
            $this->saveConcreteProducts($request, $productConcreteBulkForm, $productConcreteCollectionTransfer);

            $responseData = $this->getFactory()
                ->getZedUiFactory()
                ->createZedUiFormResponseBuilder()
                ->addSuccessNotification(
                    sprintf(
                        static::RESPONSE_NOTIFICATION_MESSAGE_SUCCESS,
                        $productConcreteCollectionTransfer->getProducts()->count()
                    )
                )
                ->addActionCloseDrawer()
                ->addActionRefreshTable()
                ->createResponse()
                ->toArray();
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
