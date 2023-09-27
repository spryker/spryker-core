<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCart\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Spryker\Zed\ServicePointCart\Dependency\Facade\ServicePointCartToServicePointFacadeInterface;

class QuoteItemServicePointValidator implements QuoteItemServicePointValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_CART_CHECKOUT_ERROR = 'service_point_cart.checkout.validation.error';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_SERVICE_POINT_UUID = '%uuid%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_STORE_NAME = '%store_name%';

    /**
     * @var \Spryker\Zed\ServicePointCart\Dependency\Facade\ServicePointCartToServicePointFacadeInterface
     */
    protected ServicePointCartToServicePointFacadeInterface $servicePointFacade;

    /**
     * @param \Spryker\Zed\ServicePointCart\Dependency\Facade\ServicePointCartToServicePointFacadeInterface $servicePointFacade
     */
    public function __construct(ServicePointCartToServicePointFacadeInterface $servicePointFacade)
    {
        $this->servicePointFacade = $servicePointFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validate(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $quoteItemsServicePointUuids = $this->extractServicePointUuids($quoteTransfer->getItems());
        if (!$quoteItemsServicePointUuids) {
            return true;
        }

        $storeName = $quoteTransfer->getStoreOrFail()->getNameOrFail();
        $servicePointCriteriaTransfer = $this->getServicePointCriteriaTransfer($quoteItemsServicePointUuids, $storeName);
        $servicePointCollectionTransfer = $this->servicePointFacade->getServicePointCollection($servicePointCriteriaTransfer);

        $checkoutErrorTransfers = $this->validateServicePoints(
            $quoteItemsServicePointUuids,
            $servicePointCollectionTransfer,
            $storeName,
        );

        if ($checkoutErrorTransfers->count()) {
            $this->addErrorsToCheckoutResponseTransfer($checkoutResponseTransfer, $checkoutErrorTransfers);

            return false;
        }

        return true;
    }

    /**
     * @param list<string> $quoteItemsServicePointUuids
     * @param \Generated\Shared\Transfer\ServicePointCollectionTransfer $servicePointCollectionTransfer
     * @param string $storeName
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\CheckoutErrorTransfer>
     */
    protected function validateServicePoints(
        array $quoteItemsServicePointUuids,
        ServicePointCollectionTransfer $servicePointCollectionTransfer,
        string $storeName
    ): ArrayObject {
        $checkoutErrorTransfers = new ArrayObject();

        $servicePointUuidsIndexedByServicePointUuids = $this->getServicePointUuidsIndexedByServicePointUuids(
            $servicePointCollectionTransfer->getServicePoints(),
        );

        foreach ($quoteItemsServicePointUuids as $servicePointUuid) {
            if (!isset($servicePointUuidsIndexedByServicePointUuids[$servicePointUuid])) {
                $checkoutErrorTransfers->append($this->createCheckoutErrorTransfer(
                    $servicePointUuid,
                    $storeName,
                ));
            }
        }

        return $checkoutErrorTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CheckoutErrorTransfer> $checkoutErrorTransfers
     *
     * @return void
     */
    protected function addErrorsToCheckoutResponseTransfer(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        ArrayObject $checkoutErrorTransfers
    ): void {
        $checkoutResponseTransfer->setIsSuccess(false);

        foreach ($checkoutErrorTransfers as $checkoutErrorTransfer) {
            $checkoutResponseTransfer->addError($checkoutErrorTransfer);
        }
    }

    /**
     * @param string $servicePointUuid
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer(string $servicePointUuid, string $storeName): CheckoutErrorTransfer
    {
        return (new CheckoutErrorTransfer())
            ->setMessage(
                static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_CART_CHECKOUT_ERROR,
            )
            ->setParameters([
                static::GLOSSARY_KEY_PARAMETER_SERVICE_POINT_UUID => $servicePointUuid,
                static::GLOSSARY_KEY_PARAMETER_STORE_NAME => $storeName,
            ]);
    }

    /**
     * @param list<string> $servicePointsUuids
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ServicePointCriteriaTransfer
     */
    protected function getServicePointCriteriaTransfer(
        array $servicePointsUuids,
        string $storeName
    ): ServicePointCriteriaTransfer {
        return (new ServicePointCriteriaTransfer())->setServicePointConditions(
            (new ServicePointConditionsTransfer())
                ->setUuids($servicePointsUuids)
                ->addStoreName($storeName)
                ->setIsActive(true),
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<string>
     */
    protected function extractServicePointUuids(ArrayObject $itemTransfers): array
    {
        $servicePointUuids = [];
        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getServicePoint()) {
                continue;
            }
            $servicePointUuid = $itemTransfer->getServicePointOrFail()->getUuidOrFail();

            $servicePointUuids[$servicePointUuid] = $servicePointUuid;
        }

        return array_values($servicePointUuids);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     *
     * @return array<string, string>
     */
    protected function getServicePointUuidsIndexedByServicePointUuids(ArrayObject $servicePointTransfers): array
    {
        $servicePointUuidsIndexedByServicePointUuids = [];
        foreach ($servicePointTransfers as $servicePointTransfer) {
            $servicePointUuid = $servicePointTransfer->getUuidOrFail();
            $servicePointUuidsIndexedByServicePointUuids[$servicePointUuid] = $servicePointUuid;
        }

        return $servicePointUuidsIndexedByServicePointUuids;
    }
}
