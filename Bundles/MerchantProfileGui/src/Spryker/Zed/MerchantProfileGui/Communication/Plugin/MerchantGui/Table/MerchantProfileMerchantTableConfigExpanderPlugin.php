<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Table;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableConfigExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 */
class MerchantProfileMerchantTableConfigExpanderPlugin extends AbstractPlugin implements MerchantTableConfigExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands merchant table config.
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expand(TableConfiguration $config): TableConfiguration
    {
        $config->addRawColumn($this->getConfig()->getIsActiveColumnName());

        return $config;
    }
}
