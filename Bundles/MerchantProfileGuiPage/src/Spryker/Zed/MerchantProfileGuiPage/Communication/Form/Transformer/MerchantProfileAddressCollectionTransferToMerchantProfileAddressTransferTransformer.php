<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Transformer;

use Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGuiPage\Communication\MerchantProfileGuiPageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGuiPage\MerchantProfileGuiPageConfig getConfig()
 */
class MerchantProfileAddressCollectionTransferToMerchantProfileAddressTransferTransformer implements DataTransformerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer $value
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function transform($value): MerchantProfileAddressTransfer
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
    public function reverseTransform($value): MerchantProfileAddressCollectionTransfer
    {
        if (!$value instanceof MerchantProfileAddressTransfer) {
            return new MerchantProfileAddressCollectionTransfer();
        }

        $merchantProfileAddressCollectionTransfer = new MerchantProfileAddressCollectionTransfer();
        $merchantProfileAddressCollectionTransfer->addAddress($value);

        return $merchantProfileAddressCollectionTransfer;
    }
}
