<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Table;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableActionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 */
class MerchantProfileMerchantTableActionExpanderPlugin extends AbstractPlugin implements MerchantTableActionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands merchant table actions.
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expand(array $item): array
    {
        $buttons = [];

        $button = $this->getFactory()
            ->createMerchantProfileChangeStatusButtonCreator()
            ->getChangeStatusButton($item[SpyMerchantTableMap::COL_ID_MERCHANT]);

        if ($button !== null) {
            $buttons[] = $button;
        }

        return $buttons;
    }
}
