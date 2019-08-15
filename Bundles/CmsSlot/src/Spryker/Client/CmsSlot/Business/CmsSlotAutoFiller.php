<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlot\Business;

use Spryker\Client\CmsSlot\Exception\SlotFillerIsMissingException;

class CmsSlotAutoFiller implements CmsSlotAutoFillerInterface
{
    /**
     * @var \Spryker\Client\CmsSlotExtension\Dependency\Plugin\CmsSlotFillerStrategyPluginInterface[]
     */
    protected $cmsSlotFillerStrategyPlugins;

    /**
     * @param \Spryker\Client\CmsSlotExtension\Dependency\Plugin\CmsSlotFillerStrategyPluginInterface[] $cmsSlotFillerStrategyPlugins
     */
    public function __construct(array $cmsSlotFillerStrategyPlugins)
    {
        $this->cmsSlotFillerStrategyPlugins = $cmsSlotFillerStrategyPlugins;
    }

    /**
     * @param string[] $fillingKeys
     *
     * @return array
     */
    public function fetchCmsSlotAutoFilled(array $fillingKeys): array
    {
        $fetchedData = [];

        foreach ($fillingKeys as $fillingKey) {
            $fetchedData[$fillingKey] = $this->applyCmsSlotFillerStrategyPlugin($fillingKey);
        }

        return $fetchedData;
    }

    /**
     * @param string $fillingKey
     *
     * @throws \Spryker\Client\CmsSlot\Exception\SlotFillerIsMissingException
     *
     * @return mixed
     */
    protected function applyCmsSlotFillerStrategyPlugin(string $fillingKey)
    {
        foreach ($this->cmsSlotFillerStrategyPlugins as $cmsSlotFillerStrategyPlugin) {
            if ($cmsSlotFillerStrategyPlugin->isApplicable($fillingKey)) {
                return $cmsSlotFillerStrategyPlugin->fill($fillingKey);
            }
        }

        throw new SlotFillerIsMissingException(sprintf('The filler for the key "%s" is missing', $fillingKey));
    }
}
