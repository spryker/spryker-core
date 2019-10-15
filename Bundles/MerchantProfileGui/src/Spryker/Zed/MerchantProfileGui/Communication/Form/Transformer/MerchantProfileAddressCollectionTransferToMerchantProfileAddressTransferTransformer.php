<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form\Transformer;

use Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 */
class MerchantProfileAddressCollectionTransferToMerchantProfileAddressTransferTransformer implements DataTransformerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer $value
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function transform($value)
    {
        if ($value instanceof MerchantProfileAddressCollectionTransfer && $value->getAddresses()->offsetExists(0)) {
            return $value->getAddresses()->offsetGet(0);
        }

        return new MerchantProfileAddressTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $value
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer
     */
    public function reverseTransform($value)
    {
        if (!$this->isValueSet($value)) {
            return new MerchantProfileAddressCollectionTransfer();
        }

        $merchantProfileAddressCollectionTransfer = new MerchantProfileAddressCollectionTransfer();
        $merchantProfileAddressCollectionTransfer->addAddress($value);

        return $merchantProfileAddressCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer|null $value
     *
     * @return bool
     */
    protected function isValueSet($value): bool
    {
        return $value && $value instanceof MerchantProfileAddressTransfer;
    }
}
