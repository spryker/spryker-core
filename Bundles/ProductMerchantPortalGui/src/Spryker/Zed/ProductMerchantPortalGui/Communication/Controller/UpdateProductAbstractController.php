<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

        $productAbstractTransfer = $this->getFactory()->getMerchantProductFacade()->findProductAbstract(
            (new MerchantProductCriteriaTransfer())->setIdMerchant($idMerchant)->setIdProductAbstract($idProductAbstract)
        );

        if (!$productAbstractTransfer) {
            throw new NotFoundHttpException(sprintf(
                'Product abstract is not found for product abstract id %d and merchant id %d.',
                $idProductAbstract,
                $idMerchant
            ));
        }

        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();
        $productAbstractName = $this->getFactory()
            ->createProductAbstractNameBuilder()
            ->buildProductAbstractName($productAbstractTransfer, $localeTransfer);

        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/product_abstract_form.twig', [
                'productAbstract' => $productAbstractTransfer,
                'productAbstractName' => $productAbstractName,
            ])->getContent(),
        ];

        return new JsonResponse($responseData);
    }
}
