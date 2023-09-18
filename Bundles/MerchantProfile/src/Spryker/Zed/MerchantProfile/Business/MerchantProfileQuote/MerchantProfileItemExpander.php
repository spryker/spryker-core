<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfileQuote;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileReaderInterface;

class MerchantProfileItemExpander implements MerchantProfileItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileReaderInterface
     */
    protected MerchantProfileReaderInterface $merchantProfileReader;

    /**
     * @param \Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileReaderInterface $merchantProfileReader
     */
    public function __construct(MerchantProfileReaderInterface $merchantProfileReader)
    {
        $this->merchantProfileReader = $merchantProfileReader;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\CalculableObjectTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function expand(OrderTransfer|CalculableObjectTransfer $transfer): OrderTransfer|CalculableObjectTransfer
    {
        $itemTransfers = $transfer->getItems();

        $merchantProfileAddressCollection = $this->getMerchantProfileAddressCollectionFromItemsIndexedByMerchantReference(
            $itemTransfers,
        );

        $this->setItemsWithMerchantProfileAddress($itemTransfers, $merchantProfileAddressCollection);

        return $transfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\MerchantProfileTransfer>
     */
    protected function getMerchantProfileAddressCollectionFromItemsIndexedByMerchantReference(ArrayObject $itemTransfers): array
    {
        return $this->merchantProfileReader->findMerchantProfileAddressesCollectionIndexedByMerchantReference(
            $this->getMerchantReferencesFromItems($itemTransfers),
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string>
     */
    protected function getMerchantReferencesFromItems(ArrayObject $itemTransfers): array
    {
        $merchantReferences = [];

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference()) {
                continue;
            }

            $merchantReferences[] = $itemTransfer->getMerchantReference();
        }

        return array_unique($merchantReferences);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array $merchantProfileAddressCollection
     *
     * @return void
     */
    protected function setItemsWithMerchantProfileAddress(ArrayObject $itemTransfers, array $merchantProfileAddressCollection): void
    {
        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference()) {
                continue;
            }

            if (!isset($merchantProfileAddressCollection[$itemTransfer->getMerchantReference()])) {
                continue;
            }

            $merchantProfileAddresses = $merchantProfileAddressCollection[$itemTransfer->getMerchantReference()];

            $itemTransfer->setMerchantProfileAddress($merchantProfileAddresses[0]);
        }
    }
}
