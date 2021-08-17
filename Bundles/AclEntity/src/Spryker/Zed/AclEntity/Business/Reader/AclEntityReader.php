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
     * @var bool
     */
    protected $isAclEntityActive;

    /**
     * @param bool $isAclEntityActive
     * @param \Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityDisablerPluginInterface[] $aclEntityDisablerPlugins
     */
    public function __construct(bool $isAclEntityActive, array $aclEntityDisablerPlugins)
    {
        $this->isAclEntityActive = $isAclEntityActive;
        $this->aclEntityDisablerPlugins = $aclEntityDisablerPlugins;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        $isActive = $this->isAclEntityActive;

        foreach ($this->aclEntityDisablerPlugins as $aclEntityDisablerPlugin) {
            if ($aclEntityDisablerPlugin->isDisabled()) {
                return false;
            }
        }

        return $isActive;
    }
}
