<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelReplicationCache\Communication\Plugin\PropelOrm;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrmExtension\Dependency\Plugin\FindExtensionPluginInterface;
use Spryker\Zed\PropelReplicationCache\Business\PropelReplicationCacheFacade;

/**
 * @method \Spryker\Zed\PropelReplicationCache\PropelReplicationCacheConfig getConfig()
 * @method \Spryker\Zed\PropelReplicationCache\Business\PropelReplicationCacheFacade getFacade()
 */
class FindExtensionPlugin extends AbstractPlugin implements FindExtensionPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getClassesToDeclare(): array
    {
        return [
            PropelReplicationCacheFacade::class,
        ];
    }

    /**
     * {@inheritDoc}
     * - Extension code checks if cache storage has a key equal to this object short class name.
     * - If key exists then master connection is set for reading (overrides default value).
     * - If key does not exist then default connection is used.
     *
     * @api
     *
     * @param string $script
     *
     * @return string
     */
    public function extend(string $script): string
    {
        $script .= "
        \$class = get_class(\$this);
        \$mustUseWriteContext = PropelReplicationCacheFacade::getInstance()->hasKey(\$class);

        if (\$mustUseWriteContext) {
            \$con = Propel::getWriteConnection(\$this->getDbName());
        } elseif (\$con === null) {
            \$con = Propel::getReadConnection(\$this->getDbName());
        }
        ";

        return $script;
    }
}
