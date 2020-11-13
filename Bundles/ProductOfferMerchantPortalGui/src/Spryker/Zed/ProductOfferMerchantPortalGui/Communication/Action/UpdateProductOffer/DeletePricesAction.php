<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Action\UpdateProductOffer;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Action\ActionInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\External\ProductOfferMerchantPortalGuiToValidationAdapterInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class DeletePricesAction implements ActionInterface
{
    protected const TYPE_REFRESH_TABLE = 'refresh_table';
    protected const TYPE_SUCCESS = 'success';
    protected const TYPE_ERROR = 'error';
    protected const SUCCESS_MESSAGE = 'Success! The Price is deleted.';
    protected const ERROR_MESSAGE = 'Something went wrong, please try again.';

    protected const PARAM_PRICE_PRODUCT_OFFER_IDS = 'price-product-offer-ids';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\External\ProductOfferMerchantPortalGuiToValidationAdapterInterface
     */
    protected $validationAdapter;

    /**
     * @var \Symfony\Component\Validator\Constraint
     */
    protected $validationConstraint;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
     */
    protected $priceProductOfferFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\External\ProductOfferMerchantPortalGuiToValidationAdapterInterface $validationAdapter
     * @param \Symfony\Component\Validator\Constraint $validationConstraint
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToValidationAdapterInterface $validationAdapter,
        SymfonyConstraint $validationConstraint,
        ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade,
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->validationAdapter = $validationAdapter;
        $this->validationConstraint = $validationConstraint;
        $this->priceProductOfferFacade = $priceProductOfferFacade;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function execute(Request $request): JsonResponse
    {
        $priceProductOfferIds = array_map('intval', $request->get(static::PARAM_PRICE_PRODUCT_OFFER_IDS));
        $response = $this->validatePriceProductOfferIds($priceProductOfferIds);

        if ($response) {
            return $response;
        }

        $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
        $priceProductOfferCriteriaTransfer->setPriceProductOfferIds($priceProductOfferIds);
        $this->priceProductOfferFacade->delete($priceProductOfferCriteriaTransfer);

        $responseData['postActions'] = [
            [
                'type' => static::TYPE_REFRESH_TABLE,
            ],
        ];
        $responseData['notifications'] = [[
            'type' => static::TYPE_SUCCESS,
            'message' => $this->translatorFacade->trans(static::SUCCESS_MESSAGE),
        ]];

        return new JsonResponse($responseData);
    }

    /**
     * @param int[] $priceProductOfferIds
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|null
     */
    protected function validatePriceProductOfferIds(array $priceProductOfferIds): ?JsonResponse
    {
        $constraintViolationList = $this->validationAdapter
            ->createValidator()
            ->validate(
                $this->createPriceProductOfferCollectionTransferByPriceProductOfferIds($priceProductOfferIds),
                $this->validationConstraint
            );

        if ($constraintViolationList->count()) {
            $responseData['notifications'][] = [
                'type' => static::TYPE_ERROR,
                'message' => $this->translatorFacade->trans(static::ERROR_MESSAGE),
            ];

            return new JsonResponse($responseData);
        }

        return null;
    }

    /**
     * @param int[] $priceProductOfferIds
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer
     */
    protected function createPriceProductOfferCollectionTransferByPriceProductOfferIds(array $priceProductOfferIds): PriceProductOfferCollectionTransfer
    {
        $priceProductOfferCollectionTransfer = new PriceProductOfferCollectionTransfer();
        $currentMerchanUser = $this->merchantUserFacade->getCurrentMerchantUser();

        foreach ($priceProductOfferIds as $idPriceProductOffer) {
            $priceProductOfferTransfer = (new PriceProductOfferTransfer())->setIdPriceProductOffer($idPriceProductOffer)
                ->setProductOffer(
                    (new ProductOfferTransfer())->setFkMerchant($currentMerchanUser->getIdMerchant())
                );
            $priceProductOfferCollectionTransfer->addPriceProductOffer($priceProductOfferTransfer);
        }

        return $priceProductOfferCollectionTransfer;
    }
}
