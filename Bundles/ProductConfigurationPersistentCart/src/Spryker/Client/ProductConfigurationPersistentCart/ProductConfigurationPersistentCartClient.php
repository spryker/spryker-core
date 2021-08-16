<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationPersistentCart;

use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductConfigurationPersistentCart\ProductConfigurationPersistentCartFactory getFactory()
 */
class ProductConfigurationPersistentCartClient extends AbstractClient implements ProductConfigurationPersistentCartClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandPersistentCartChangeWithProductConfigurationInstance(
        PersistentCartChangeTransfer $persistentCartChangeTransfer,
        array $params
    ): PersistentCartChangeTransfer {
        return $this->getFactory()
            ->createProductConfigurationInstanceCartChangeExpander()
            ->expandPersistentCartChangeWithProductConfigurationInstance($persistentCartChangeTransfer, $params);
    }
}
