<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlot\Business;

use Spryker\Client\CmsSlot\Exception\SlotDataProviderMissingException;

class CmsSlotDataProvider implements CmsSlotDataProviderInterface
{
    /**
     * @var \Spryker\Client\CmsSlotExtension\Dependency\Plugin\CmsSlotExternalDataProviderStrategyPluginInterface[]
     */
    protected $cmsSlotExternalDataProviderStrategyPlugins;

    /**
     * @param \Spryker\Client\CmsSlotExtension\Dependency\Plugin\CmsSlotExternalDataProviderStrategyPluginInterface[] $cmsSlotExternalDataProviderStrategyPlugins
     */
    public function __construct(array $cmsSlotExternalDataProviderStrategyPlugins)
    {
        $this->cmsSlotExternalDataProviderStrategyPlugins = $cmsSlotExternalDataProviderStrategyPlugins;
    }

    /**
     * @param string[] $dataKeys
     *
     * @return array
     */
    public function getCmsSlotExternalDataByKeys(array $dataKeys): array
    {
        $externalData = [];

        foreach ($dataKeys as $dataKey) {
            $externalData[$dataKey] = $this->applyCmsSlotExternalDataProviderStrategyPlugin($dataKey);
        }

        return $externalData;
    }

    /**
     * @param string $dataKey
     *
     * @throws \Spryker\Client\CmsSlot\Exception\SlotDataProviderMissingException
     *
     * @return mixed
     */
    protected function applyCmsSlotExternalDataProviderStrategyPlugin(string $dataKey)
    {
        foreach ($this->cmsSlotExternalDataProviderStrategyPlugins as $cmsSlotExternalDataProviderStrategyPlugin) {
            if ($cmsSlotExternalDataProviderStrategyPlugin->isApplicable($dataKey)) {
                return $cmsSlotExternalDataProviderStrategyPlugin->getDataForKey($dataKey);
            }
        }

        throw new SlotDataProviderMissingException(sprintf('The data provider for the key "%s" is missing', $dataKey));
    }
}
