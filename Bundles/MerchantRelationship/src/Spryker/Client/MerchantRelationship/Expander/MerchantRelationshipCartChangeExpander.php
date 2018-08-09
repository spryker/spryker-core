<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRelationship\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;

class MerchantRelationshipCartChangeExpander implements MerchantRelationshipCartChangeExpanderInterface
{
    protected const URL_PARAM_ID_MERCHANT_RELATIONSHIP = 'id-merchant-relationship';

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithMerchantRelationship(CartChangeTransfer $cartChangeTransfer, array $params): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $this->expandItemWithMerchantRelationship($itemTransfer, $params);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandPersistentCartChangeTransferWithMerchantRelationship(PersistentCartChangeTransfer $cartChangeTransfer, array $params): PersistentCartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $this->expandItemWithMerchantRelationship($itemTransfer, $params);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return void
     */
    protected function expandItemWithMerchantRelationship(ItemTransfer $itemTransfer, array $params): void
    {
        if (isset($params[static::URL_PARAM_ID_MERCHANT_RELATIONSHIP][$itemTransfer->getSku()])) {
            $itemTransfer->setIdMerchantRelationship($params[static::URL_PARAM_ID_MERCHANT_RELATIONSHIP][$itemTransfer->getSku()]);
        }
    }
}
