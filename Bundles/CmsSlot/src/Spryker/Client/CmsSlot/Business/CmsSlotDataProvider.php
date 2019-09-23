<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlot\Business;

use Spryker\Client\CmsSlot\Exception\CmsSlotExternalDataProviderMissingException;

class CmsSlotDataProvider implements CmsSlotDataProviderInterface
{
    /**
     * @var \Spryker\Client\CmsSlotExtension\Dependency\Plugin\ExternalDataProviderStrategyPluginInterface[]
     */
    protected $externalDataProviderStrategyPlugins;

    /**
     * @param \Spryker\Client\CmsSlotExtension\Dependency\Plugin\ExternalDataProviderStrategyPluginInterface[] $externalDataProviderStrategyPlugins
     */
    public function __construct(array $externalDataProviderStrategyPlugins)
    {
        $this->externalDataProviderStrategyPlugins = $externalDataProviderStrategyPlugins;
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
            $externalData[$dataKey] = $this->executeExternalDataProviderStrategyPlugins($dataKey);
        }

        return $externalData;
    }

    /**
     * @param string $dataKey
     *
     * @throws \Spryker\Client\CmsSlot\Exception\CmsSlotExternalDataProviderMissingException
     *
     * @return mixed
     */
    protected function executeExternalDataProviderStrategyPlugins(string $dataKey)
    {
        foreach ($this->externalDataProviderStrategyPlugins as $externalDataProviderStrategyPlugin) {
            if ($externalDataProviderStrategyPlugin->isApplicable($dataKey)) {
                return $externalDataProviderStrategyPlugin->getDataForKey();
            }
        }

        throw new CmsSlotExternalDataProviderMissingException(sprintf('The data provider for the key "%s" is missing', $dataKey));
    }
}
