<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferResponseTransfer;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class AbstractProductOfferController extends AbstractController
{
    /**
     * @var array
     */
    protected const DEFAULT_INITIAL_DATA = [
        GuiTableEditableInitialDataTransfer::DATA => [],
        GuiTableEditableInitialDataTransfer::ERRORS => [],
    ];

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'The Offer is saved.';

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_ERROR = 'To save an Offer please resolve all errors.';

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array<string>
     */
    protected function getProductAttributes(
        LocaleTransfer $localeTransfer,
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): array {
        $productAbstractLocalizedAttributes = $this->getLocalizedAttributesByLocale(
            $productAbstractTransfer->getLocalizedAttributes()->getArrayCopy(),
            $localeTransfer
        );
        $productConcreteLocalizedAttributes = $this->getLocalizedAttributesByLocale(
            $productConcreteTransfer->getLocalizedAttributes()->getArrayCopy(),
            $localeTransfer
        );
        $rawProductAttributesTransfer = (new RawProductAttributesTransfer())
            ->setAbstractAttributes($productAbstractTransfer->getAttributes())
            ->setAbstractLocalizedAttributes($productAbstractLocalizedAttributes)
            ->setConcreteAttributes($productConcreteTransfer->getAttributes())
            ->setConcreteLocalizedAttributes($productConcreteLocalizedAttributes);

        return $this->getFactory()->getProductFacade()->combineRawProductAttributes($rawProductAttributesTransfer);
    }

    /**
     * @phpstan-param array<\Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributes
     *
     * @param array<\Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributes
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<string>
     */
    protected function getLocalizedAttributesByLocale(array $localizedAttributes, LocaleTransfer $localeTransfer): array
    {
        foreach ($localizedAttributes as $localizedAttributesTransfer) {
            $localeFromLocalizedAttributes = $localizedAttributesTransfer->getLocale();

            if (!$localeFromLocalizedAttributes) {
                continue;
            }

            if ($localeFromLocalizedAttributes->getIdLocale() === $localeTransfer->getIdLocale()) {
                return $localizedAttributesTransfer->getAttributes();
            }
        }

        return [];
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

        if (!$requestTableData) {
            return static::DEFAULT_INITIAL_DATA;
        }

        $requestTableData = $this->getFactory()->getUtilEncodingService()->decodeJson(
            $requestTableData[PriceProductOfferTableViewTransfer::PRICES],
            true
        );

        if (!$requestTableData) {
            return static::DEFAULT_INITIAL_DATA;
        }

        $defaultInitialData = static::DEFAULT_INITIAL_DATA;
        $defaultInitialData[GuiTableEditableInitialDataTransfer::DATA] = $requestTableData;

        return $defaultInitialData;
    }

    /**
     * @param array<mixed> $responseData
     *
     * @return array<mixed>
     */
    protected function addSuccessResponseDataToResponse(array $responseData): array
    {
        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addSuccessNotification(static::RESPONSE_NOTIFICATION_MESSAGE_SUCCESS)
            ->createResponse();

        return array_merge($responseData, $zedUiFormResponseTransfer->toArray());
    }

    /**
     * @phpstan-param array<string, mixed> $responseData
     *
     * @phpstan-return array<string, mixed>
     *
     * @param array<mixed> $responseData
     * @param \Generated\Shared\Transfer\ProductOfferResponseTransfer|null $productOfferResponseTransfer
     *
     * @return array<mixed>
     */
    protected function addErrorResponseDataToResponse(array $responseData, ?ProductOfferResponseTransfer $productOfferResponseTransfer = null): array
    {
        $message = $this->getFactory()
            ->getTranslatorFacade()
            ->trans(static::RESPONSE_NOTIFICATION_MESSAGE_ERROR);

        $zedUiFormResponseBuilder = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addErrorNotification($message);

        if ($productOfferResponseTransfer) {
            foreach ($productOfferResponseTransfer->getErrors() as $productOfferErrorTransfer) {
                $zedUiFormResponseBuilder->addErrorNotification($productOfferErrorTransfer->getMessageOrFail());
            }
        }

        return array_merge($responseData, $zedUiFormResponseBuilder->createResponse()->toArray());
    }
}
