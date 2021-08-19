<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferResponseTransfer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class UpdateProductOfferController extends AbstractProductOfferController
{
    protected const PARAM_ID_PRODUCT_OFFER = 'product-offer-id';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_WAITING_FOR_APPROVAL
     */
    protected const APPROVAL_STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     */
    protected const APPROVAL_STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_DENIED
     */
    protected const APPROVAL_STATUS_DENIED = 'denied';

    protected const APPROVAL_STATUS_WAITING_FOR_APPROVAL_CHIP_TITLE = 'Pending';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idProductOffer = $this->castId($request->get(static::PARAM_ID_PRODUCT_OFFER));
        $productOfferUpdateFormDataProvider = $this->getFactory()->createProductOfferUpdateFormDataProvider();
        $productOfferTransfer = $productOfferUpdateFormDataProvider->getData($idProductOffer);

        if (!$productOfferTransfer) {
            throw new NotFoundHttpException(sprintf('Product offer is not found for id %d.', $idProductOffer));
        }

        $idProductConcrete = $productOfferTransfer->getIdProductConcreteOrFail();
        $productConcreteTransfer = $this->getFactory()->getProductFacade()->findProductConcreteById($idProductConcrete);
        if (!$productConcreteTransfer) {
            throw new NotFoundHttpException(sprintf('Product is not found for id %d.', $idProductConcrete));
        }

        $idProductAbstract = $productConcreteTransfer->getFkProductAbstractOrFail();
        /** @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer */
        $productAbstractTransfer = $this->getFactory()->getProductFacade()->findProductAbstractById($idProductAbstract);

        $productOfferForm = $this->getFactory()->createProductOfferForm(
            $productOfferTransfer,
            $productOfferUpdateFormDataProvider->getOptions($productAbstractTransfer)
        );
        $productOfferForm->handleRequest($request);

        $initialData = $this->getDefaultInitialData($request, $productOfferForm->getName());
        $productOfferResponseTransfer = new ProductOfferResponseTransfer();
        $productOfferResponseTransfer->setProductOffer($productOfferTransfer);

        if (!$productOfferForm->isSubmitted()) {
            return $this->getResponse(
                $productOfferForm,
                $productConcreteTransfer,
                $productAbstractTransfer,
                $productOfferResponseTransfer,
                $idProductOffer,
                $initialData
            );
        }

        $priceProductOfferTransfer = (new PriceProductOfferTransfer())
            ->setProductOffer($productOfferForm->getData());

        $priceProductOfferCollectionTransfer = (new PriceProductOfferCollectionTransfer())
            ->addPriceProductOffer($priceProductOfferTransfer);

        $validationResponseTransfer = $this->getFactory()
            ->createPriceProductOfferValidator()
            ->validatePriceProductOfferCollection($priceProductOfferCollectionTransfer);

        if (!$productOfferForm->isValid() || !$validationResponseTransfer->getIsSuccess()) {
            $validationResponseTransfer = $this->getFactory()
                ->createValidationResponseTranslator()
                ->translateValidationResponse($validationResponseTransfer);

            $initialData = $this->getFactory()
                ->createPriceProductOfferMapper()
                ->mapValidationResponseTransferToInitialDataErrors(
                    $validationResponseTransfer,
                    $priceProductOfferCollectionTransfer,
                    $initialData
                );

            return $this->getResponse(
                $productOfferForm,
                $productConcreteTransfer,
                $productAbstractTransfer,
                $productOfferResponseTransfer,
                $idProductOffer,
                $initialData
            );
        }

        $productOfferResponseTransfer = $this->getFactory()->getProductOfferFacade()->update($productOfferForm->getData());

        return $this->getResponse(
            $productOfferForm,
            $productConcreteTransfer,
            $productAbstractTransfer,
            $productOfferResponseTransfer,
            $idProductOffer,
            $initialData
        );
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productOfferForm
     *
     * @param \Symfony\Component\Form\FormInterface $productOfferForm
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductOfferResponseTransfer $productOfferResponseTransfer
     * @param int $idProductOffer
     * @param mixed[] $initialData
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productOfferForm,
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer,
        ProductOfferResponseTransfer $productOfferResponseTransfer,
        int $idProductOffer,
        array $initialData
    ): JsonResponse {
        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        $productOfferTransfer = $productOfferResponseTransfer->getProductOffer();
        $productOfferReference = $productOfferTransfer ? $productOfferTransfer->getProductOfferReference() : null;
        $approvalStatus = $productOfferResponseTransfer->getProductOfferOrFail()->getApprovalStatus() === static::APPROVAL_STATUS_WAITING_FOR_APPROVAL
            ? static::APPROVAL_STATUS_WAITING_FOR_APPROVAL_CHIP_TITLE
            : $productOfferResponseTransfer->getProductOfferOrFail()->getApprovalStatus();

        $priceProductOfferTableConfiguration = $this->getFactory()
            ->createPriceProductOfferUpdateGuiTableConfigurationProvider()
            ->getConfiguration($idProductOffer, $initialData);

        $responseData = [
            'form' => $this->renderView('@ProductOfferMerchantPortalGui/Partials/offer_form.twig', [
                'form' => $productOfferForm->createView(),
                'product' => $productConcreteTransfer,
                'productName' => $this->getFactory()->createProductNameBuilder()->buildProductConcreteName($productConcreteTransfer, $localeTransfer),
                'productAttributes' => $this->getProductAttributes($localeTransfer, $productConcreteTransfer, $productAbstractTransfer),
                'productOfferReference' => $productOfferReference,
                'priceProductOfferTableConfiguration' => $priceProductOfferTableConfiguration,
                'approvalStatus' => $approvalStatus,
                'approvalStatusChipColors' => [
                    static::APPROVAL_STATUS_APPROVED => 'green',
                    static::APPROVAL_STATUS_DENIED => 'red',
                    static::APPROVAL_STATUS_WAITING_FOR_APPROVAL_CHIP_TITLE => 'yellow',
                ],
            ])->getContent(),
        ];

        if (!$productOfferForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        $isPriceProductOffersValid = count($initialData['errors']) === 0;

        if (
            $productOfferForm->isValid()
            && $productOfferResponseTransfer->getIsSuccessful()
            && $isPriceProductOffersValid
        ) {
            $responseData = $this->addSuccessResponseDataToResponse($responseData);

            return new JsonResponse($responseData);
        }

        $responseData = $this->addErrorResponseDataToResponse($responseData, $productOfferResponseTransfer);

        return new JsonResponse($responseData);
    }

    /**
     * @param mixed[] $responseData
     *
     * @return mixed[]
     */
    protected function addSuccessResponseDataToResponse(array $responseData): array
    {
        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addSuccessNotification(
                $this->getFactory()->getTranslatorFacade()->trans(static::RESPONSE_NOTIFICATION_MESSAGE_SUCCESS)
            )
            ->addActionCloseDrawer()
            ->addActionRefreshTable()
            ->createResponse();

        return array_merge($responseData, $zedUiFormResponseTransfer->toArray());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function priceTableDataAction(Request $request): Response
    {
        $idProductOffer = (int)$request->get(static::PARAM_ID_PRODUCT_OFFER);

        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createProductOfferPriceTableDataProvider($idProductOffer),
            $this->getFactory()->createPriceProductOfferUpdateGuiTableConfigurationProvider()->getConfiguration($idProductOffer, [])
        );
    }
}
