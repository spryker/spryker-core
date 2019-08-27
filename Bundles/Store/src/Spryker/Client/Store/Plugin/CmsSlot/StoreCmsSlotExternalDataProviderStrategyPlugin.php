<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store\Plugin\CmsSlot;

use Spryker\Client\CmsSlotExtension\Dependency\Plugin\CmsSlotExternalDataProviderStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Store\StoreClientInterface getClient()
 */
class StoreCmsSlotExternalDataProviderStrategyPlugin extends AbstractPlugin implements CmsSlotExternalDataProviderStrategyPluginInterface
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
     *  - Returns the current store as StoreTransfer.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|mixed
     */
    public function getDataForKey()
    {
        return $this->getClient()->getCurrentStore();
    }
}
