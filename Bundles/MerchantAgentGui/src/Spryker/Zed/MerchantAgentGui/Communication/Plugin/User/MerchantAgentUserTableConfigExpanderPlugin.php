<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAgentGui\Communication\Plugin\User;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTableConfigExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantAgentGui\Communication\MerchantAgentGuiCommunicationFactory getFactory()
 */
class MerchantAgentUserTableConfigExpanderPlugin extends AbstractPlugin implements UserTableConfigExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the user's table with the `isMerchantAgent` column.
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        return $this->getFactory()->createMerchantAgentUserTableConfigExpander()->expandConfig($config);
    }
}
