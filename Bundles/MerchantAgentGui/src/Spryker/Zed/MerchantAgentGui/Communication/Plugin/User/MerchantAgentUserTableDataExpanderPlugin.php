<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAgentGui\Communication\Plugin\User;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTableDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantAgentGui\Communication\MerchantAgentGuiCommunicationFactory getFactory()
 */
class MerchantAgentUserTableDataExpanderPlugin extends AbstractPlugin implements UserTableDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the user's table `isMerchantAgent` column with data.
     *
     * @api
     *
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    public function expandData(array $item): array
    {
        return $this->getFactory()->createMerchantAgentUserTableDataExpander()->expandData($item);
    }
}
