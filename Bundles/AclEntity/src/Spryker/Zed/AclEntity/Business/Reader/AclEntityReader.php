<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Reader;

class AclEntityReader implements AclEntityReaderInterface
{
    /**
     * @var \Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityDisablerPluginInterface[]
     */
    protected $aclEntityDisablerPlugins;

    /**
     * @var \Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityEnablerPluginInterface[]
     */
    protected $aclEntityEnablerPlugins;

    /**
     * @param \Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityDisablerPluginInterface[] $aclEntityDisablerPlugins
     * @param \Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityEnablerPluginInterface[] $aclEntityEnablerPlugins
     */
    public function __construct(array $aclEntityDisablerPlugins, array $aclEntityEnablerPlugins)
    {
        $this->aclEntityDisablerPlugins = $aclEntityDisablerPlugins;
        $this->aclEntityEnablerPlugins = $aclEntityEnablerPlugins;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        $isActive = false;
        foreach ($this->aclEntityEnablerPlugins as $aclEntityEnablerPlugin) {
            if ($aclEntityEnablerPlugin->isEnabled()) {
                $isActive = true;
            }
        }

        foreach ($this->aclEntityDisablerPlugins as $aclEntityDisablerPlugin) {
            if ($aclEntityDisablerPlugin->isDisabled()) {
                return false;
            }
        }

        return $isActive;
    }
}
