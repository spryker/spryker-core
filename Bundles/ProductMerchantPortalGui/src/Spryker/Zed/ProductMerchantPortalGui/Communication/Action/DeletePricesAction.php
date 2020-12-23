<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Action;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DeletePricesAction implements ActionInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface
     */
    protected $merchantProductFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade,
        ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->merchantProductFacade = $merchantProductFacade;
        $this->priceProductFacade = $priceProductFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function execute(Request $request): JsonResponse
    {
        $priceProductDefaultIds = array_map(
            'intval',
            $this->utilEncodingService->decodeJson($request->get(PriceProductAbstractTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS), true)
        );
        $idProductAbstract = (int)$request->get(PriceProductAbstractTableViewTransfer::ID_PRODUCT_ABSTRACT);

        if (!$idProductAbstract) {
            return $this->getErrorResponse();
        }

        $idMerchant = $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant();
        $productAbstractTransfer = $this->merchantProductFacade->findProductAbstract(
            (new MerchantProductCriteriaTransfer())->addIdMerchant($idMerchant)->setIdProductAbstract($idProductAbstract)
        );

        if (!$productAbstractTransfer) {
            return $this->getErrorResponse();
        }

        $priceProductTransfersToRemove = $this->getProductTransfersToRemove($productAbstractTransfer, $priceProductDefaultIds);

        foreach ($priceProductTransfersToRemove as $priceProductTransfer) {
            $this->priceProductFacade->removePriceProductDefaultForPriceProduct($priceProductTransfer);
        }

        $responseData = [
            'postActions' => [
                'type' => 'refresh_table',
            ],
            'notifications' => [
                [
                    'type' => 'success',
                    'message' => 'Success! The Price is deleted.',
                ],
            ],
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getErrorResponse(): JsonResponse
    {
        $responseData['notifications'][] = [
            'type' => 'error',
            'message' => 'Something went wrong, please try again.',
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param int[] $priceProductDefaultIds
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getProductTransfersToRemove(
        ProductAbstractTransfer $productAbstractTransfer,
        array $priceProductDefaultIds
    ): array {
        $priceProductTransfersToRemove = [];

        foreach ($productAbstractTransfer->getPrices() as $priceProductTransfer) {
            if (in_array($priceProductTransfer->getPriceDimension()->getIdPriceProductDefault(), $priceProductDefaultIds)) {
                $priceProductTransfersToRemove[] = $priceProductTransfer;
            }
        }

        return $priceProductTransfersToRemove;
    }
}
