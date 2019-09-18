<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotLocaleConnector\Plugin\CmsSlot;

use Spryker\Client\CmsSlotExtension\Dependency\Plugin\ExternalDataProviderStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\CmsSlotLocaleConnector\CmsSlotLocaleConnectorFactory getFactory()
 * @method \Spryker\Client\Locale\LocaleClientInterface getClient()
 */
class LocaleExternalDataProviderStrategyPlugin extends AbstractPlugin implements ExternalDataProviderStrategyPluginInterface
{
    protected const DATA_KEY = 'locale';

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
     *  - Returns the current locale name.
     *
     * @api
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->getFactory()->getLocaleClient()->getCurrentLocale();
    }
}
