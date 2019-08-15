<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlot;

use Spryker\Client\CmsSlot\Business\CmsSlotAutoFiller;
use Spryker\Client\CmsSlot\Business\CmsSlotAutoFillerInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CmsSlotFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsSlot\Business\CmsSlotAutoFillerInterface
     */
    public function createCmsSlotAutoFiller(): CmsSlotAutoFillerInterface
    {
        return new CmsSlotAutoFiller($this->getCmsSlotFillerStrategyPlugin());
    }

    /**
     * @return \Spryker\Client\CmsSlotExtension\Dependency\Plugin\CmsSlotFillerStrategyPluginInterface[]
     */
    public function getCmsSlotFillerStrategyPlugin(): array
    {
        return $this->getProvidedDependency(CmsSlotDependencyProvider::CMS_SLOT_FILLER_STRATEGY_PLUGINS);
    }
}
