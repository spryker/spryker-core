<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRelationship\Plugin\PersistentCart;

use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\PersistentCartChangeExpanderPluginInterface;

/**
 * @method \Spryker\Client\MerchantRelationship\MerchantRelationshipClientInterface getClient()
 */
class MerchantRelationshipPersistentCartChangeRequestExpanderPlugin extends AbstractPlugin implements PersistentCartChangeExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function extend(PersistentCartChangeTransfer $cartChangeTransfer, array $params = []): PersistentCartChangeTransfer
    {
        return $this->getClient()
            ->expandPersistentCartChangeTransferWithMerchantRelationship($cartChangeTransfer, $params);
    }
}
