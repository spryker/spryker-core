<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\Transformer;

use Generated\Shared\Transfer\MerchantAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantAddressTransfer;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantGui\MerchantGuiConfig getConfig()
 */
class MerchantAddressCollectionTransferToMerchantAddressTransferTransformer implements DataTransformerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantAddressCollectionTransfer $value
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function transform($value)
    {
        if ($value instanceof MerchantAddressCollectionTransfer && $value->getAddresses()->offsetExists(0)) {
            return $value->getAddresses()->offsetGet(0);
        }

        return new MerchantAddressTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $value
     *
     * @return \Generated\Shared\Transfer\MerchantAddressCollectionTransfer
     */
    public function reverseTransform($value)
    {
        if (!$this->isValueSet($value)) {
            return new MerchantAddressCollectionTransfer();
        }

        $merchantAddressCollectionTransfer = new MerchantAddressCollectionTransfer();
        $merchantAddressCollectionTransfer->addAddress($value);

        return $merchantAddressCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer|null $value
     *
     * @return bool
     */
    protected function isValueSet($value): bool
    {
        return $value && $value instanceof MerchantAddressTransfer;
    }
}
