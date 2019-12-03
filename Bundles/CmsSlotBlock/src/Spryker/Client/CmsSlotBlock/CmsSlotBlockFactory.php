<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlock;

use Spryker\Client\CmsSlotBlock\Resolver\CmsSlotBlockVisibilityResolver;
use Spryker\Client\CmsSlotBlock\Resolver\CmsSlotBlockVisibilityResolverInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CmsSlotBlock\CmsSlotBlockConfig getConfig()
 */
class CmsSlotBlockFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsSlotBlock\Resolver\CmsSlotBlockVisibilityResolverInterface
     */
    public function createCmsSlotBlockVisibilityResolver(): CmsSlotBlockVisibilityResolverInterface
    {
        return new CmsSlotBlockVisibilityResolver(
            $this->getConfig(),
            $this->getCmsSlotBlockVisibilityResolverPlugins()
        );
    }

    /**
     * @return \Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin\CmsSlotBlockVisibilityResolverPluginInterface[]
     */
    public function getCmsSlotBlockVisibilityResolverPlugins(): array
    {
        return $this->getProvidedDependency(CmsSlotBlockDependencyProvider::PLUGINS_CMS_SLOT_BLOCK_VISIBILITY_RESOLVER);
    }
}
