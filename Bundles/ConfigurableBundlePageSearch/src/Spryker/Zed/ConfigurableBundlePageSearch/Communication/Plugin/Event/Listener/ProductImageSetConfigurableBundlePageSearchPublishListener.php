<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Business\ConfigurableBundlePageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Communication\ConfigurableBundlePageSearchCommunicationFactory getFactory()
 */
class ProductImageSetConfigurableBundlePageSearchPublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritDoc}
     * - Publishes configurable bundle templates.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $configurableBundleTemplateIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventEntityTransfers, SpyProductImageSetTableMap::COL_FK_RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE);

        $this->getFacade()->publishConfigurableBundleTemplates($configurableBundleTemplateIds);
    }
}
