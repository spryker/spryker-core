<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Locale\Plugin\CmsSlot;

use Spryker\Client\CmsSlotExtension\Dependency\Plugin\CmsSlotExternalDataProviderStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Locale\LocaleClientInterface getClient()
 */
class LocaleCmsSlotExternalDataProviderStrategyPlugin extends AbstractPlugin implements CmsSlotExternalDataProviderStrategyPluginInterface
{
    protected const FILLING_KEY = 'locale';

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
        return $dataKey === static::FILLING_KEY;
    }

    /**
     * {@inheritdoc}
     *  - Returns the current locale's name.
     *
     * @api
     *
     * @param string $dataKey
     *
     * @return string|mixed
     */
    public function getDataForKey(string $dataKey)
    {
        return $this->getClient()->getCurrentLocale();
    }
}
