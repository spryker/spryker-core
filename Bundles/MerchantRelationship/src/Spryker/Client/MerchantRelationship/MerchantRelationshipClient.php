<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRelationship;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\MerchantRelationship\MerchantRelationshipFactory getFactory()
 */
class MerchantRelationshipClient extends AbstractClient implements MerchantRelationshipClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithMerchantRelationship(CartChangeTransfer $cartChangeTransfer, array $params): CartChangeTransfer
    {
        return $this->getFactory()
            ->createMerchantRelationshipCartChangeExpander()
            ->expandCartChangeWithMerchantRelationship($cartChangeTransfer, $params);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandPersistentCartChangeTransferWithMerchantRelationship(PersistentCartChangeTransfer $cartChangeTransfer, array $params): PersistentCartChangeTransfer
    {
        return $this->getFactory()
            ->createMerchantRelationshipCartChangeExpander()
            ->expandPersistentCartChangeTransferWithMerchantRelationship($cartChangeTransfer, $params);
    }
}
