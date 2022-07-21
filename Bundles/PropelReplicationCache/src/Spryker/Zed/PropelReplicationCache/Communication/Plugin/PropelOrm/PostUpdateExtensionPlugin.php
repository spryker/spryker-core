<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelReplicationCache\Communication\Plugin\PropelOrm;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrmExtension\Dependency\Plugin\PostUpdateExtensionPluginInterface;
use Spryker\Zed\PropelReplicationCache\Business\PropelReplicationCacheFacade;

/**
 * @method \Spryker\Zed\PropelReplicationCache\PropelReplicationCacheConfig getConfig()
 * @method \Spryker\Zed\PropelReplicationCache\Business\PropelReplicationCacheFacadeInterface getFacade()
 */
class PostUpdateExtensionPlugin extends AbstractPlugin implements PostUpdateExtensionPluginInterface
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
     * - Extension code sets a key to the cache storage equal to this object short class name.
     * - This key could be used while selecting data from database.
     * - If exists - allows to set master connection for reading from DB.
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
        PropelReplicationCacheFacade::getInstance()->setKey(\$class);
        ";

        return $script;
    }
}
