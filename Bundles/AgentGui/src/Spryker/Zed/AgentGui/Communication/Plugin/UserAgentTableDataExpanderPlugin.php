<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentGui\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTableDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\AgentGui\Communication\AgentGuiCommunicationFactory getFactory()
 */
class UserAgentTableDataExpanderPlugin extends AbstractPlugin implements UserTableDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array
    {
        return $this->getFactory()
            ->createUserAgentTableDataExpander()
            ->expandData($item);
    }
}
