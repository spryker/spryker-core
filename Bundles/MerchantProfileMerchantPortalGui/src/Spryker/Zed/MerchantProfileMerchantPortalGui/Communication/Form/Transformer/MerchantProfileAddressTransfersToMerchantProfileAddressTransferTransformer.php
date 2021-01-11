<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Transformer;

use ArrayAccess;
use ArrayObject;
use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\MerchantProfileMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiConfig getConfig()
 */
class MerchantProfileAddressTransfersToMerchantProfileAddressTransferTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MerchantProfileAddressTransfer[] $value
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function transform($value): MerchantProfileAddressTransfer
    {
        if ($value instanceof ArrayAccess && $value->offsetExists(0)) {
            return $value->offsetGet(0);
        }

        return new MerchantProfileAddressTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $value
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MerchantProfileAddressTransfer[]
     */
    public function reverseTransform($value): ArrayObject
    {
        $merchantProfileAddressTransfers = new ArrayObject();
        if (!$value instanceof MerchantProfileAddressTransfer) {
            return $merchantProfileAddressTransfers;
        }

        $merchantProfileAddressTransfers->append($value);

        return $merchantProfileAddressTransfers;
    }
}
