<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotStoreConnector\Plugin\CmsSlot;

use Spryker\Client\CmsSlotExtension\Dependency\Plugin\ExternalDataProviderStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\CmsSlotStoreConnector\CmsSlotStoreConnectorFactory getFactory()
 * @method \Spryker\Client\Store\StoreClientInterface getClient()
 */
class StoreExternalDataProviderStrategyPlugin extends AbstractPlugin implements ExternalDataProviderStrategyPluginInterface
{
    protected const DATA_KEY = 'store';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $dataKey
     *
     * @return bool
     */
    public function isApplicable(string $dataKey): bool
    {
        return $dataKey === static::DATA_KEY;
    }

    /**
     * {@inheritdoc}
     *  - Returns the current store name.
     *
     * @api
     *
     * @return string
     */
    public function getDataForKey(): string
    {
        return $this->getFactory()->getStoreClient()->getCurrentStore()->getName();
    }
}
