<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRelationship\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;

interface MerchantRelationshipCartChangeExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithMerchantRelationship(CartChangeTransfer $cartChangeTransfer, array $params): CartChangeTransfer;

    /**
     * @param \Spryker\Client\MerchantRelationship\Expander\PersistentCartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Spryker\Client\MerchantRelationship\Expander\PersistentCartChangeTransfer
     */
    public function expandPersistentCartChangeTransferWithMerchantRelationship(PersistentCartChangeTransfer $cartChangeTransfer, array $params): PersistentCartChangeTransfer;
}
