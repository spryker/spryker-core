<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Table;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 */
class MerchantProfileMerchantTableDataExpanderPlugin extends AbstractPlugin implements MerchantTableDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands merchant table data.
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expand(array $item): array
    {
        $activeLabel = $this->getFactory()
            ->createMerchantProfileActiveLabelCreator()
            ->getActiveLabel($item[SpyMerchantTableMap::COL_ID_MERCHANT]);

        return [$this->getConfig()->getIsActiveColumnName() => $activeLabel];
    }
}
