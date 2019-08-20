<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlot;

use Spryker\Client\CmsSlot\Business\CmsSlotDataProvider;
use Spryker\Client\CmsSlot\Business\CmsSlotDataProviderInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CmsSlotFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsSlot\Business\CmsSlotDataProviderInterface
     */
    public function createCmsSlotDataProvider(): CmsSlotDataProviderInterface
    {
        return new CmsSlotDataProvider($this->getCmsSlotExternalDataProviderStrategyPlugin());
    }

    /**
     * @return \Spryker\Client\CmsSlotExtension\Dependency\Plugin\CmsSlotExternalDataProviderStrategyPluginInterface[]
     */
    public function getCmsSlotExternalDataProviderStrategyPlugin(): array
    {
        return $this->getProvidedDependency(CmsSlotDependencyProvider::CMS_SLOT_EXTERNAL_DATA_PROVIDER_STRATEGY_PLUGINS);
    }
}
