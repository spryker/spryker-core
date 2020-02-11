<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentGui\Communication\Plugin;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTableConfigExpanderPluginInterface;

/**
 * @method \Spryker\Zed\AgentGui\Communication\AgentGuiCommunicationFactory getFactory()
 */
class UserAgentTableConfigExpanderPlugin extends AbstractPlugin implements UserTableConfigExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        return $this->getFactory()
            ->createUserAgentTableConfigExpander()
            ->expandConfig($config);
    }
}
