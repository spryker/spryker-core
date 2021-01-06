<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
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
        if ($value instanceof ArrayObject && $value->offsetExists(0)) {
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
        if (!$value instanceof MerchantProfileAddressTransfer) {
            return new ArrayObject();
        }

        $merchantProfileAddressTransfers = new ArrayObject();
        $merchantProfileAddressTransfers->append($value);

        return $merchantProfileAddressTransfers;
    }
}
