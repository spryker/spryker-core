<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Table;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableHeaderExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 */
class MerchantProfileMerchantTableHeaderExpanderPlugin extends AbstractPlugin implements MerchantTableHeaderExpanderPluginInterface
{
    protected const COL_IS_ACTIVE_LABEL = 'active';

    /**
     * {@inheritDoc}
     * - Expands merchant table header by new columns.
     *
     * @api
     *
     * @return array
     */
    public function expand(): array
    {
        return [$this->getConfig()->getIsActiveColumnName() => static::COL_IS_ACTIVE_LABEL];
    }
}
