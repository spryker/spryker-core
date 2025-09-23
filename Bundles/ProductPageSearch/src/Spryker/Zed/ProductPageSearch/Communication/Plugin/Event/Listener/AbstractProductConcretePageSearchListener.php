<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Generated\Shared\Transfer\HydrateEventsResponseTransfer;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
abstract class AbstractProductConcretePageSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @param array<int, int> $productIdTimestampMap
     *
     * @return void
     */
    protected function publish(array $productIdTimestampMap): void
    {
        $this->getFacade()->publishWithTimestamp($productIdTimestampMap);
    }

    /**
     * @param array<int, int> $productIdTimestampMap
     *
     * @return void
     */
    protected function unpublish(array $productIdTimestampMap): void
    {
        $this->getBusinessFactory()->createProductConcretePageSearchPublisher()->unpublishWithTimestamp($productIdTimestampMap);
    }

    /**
     * @param \Generated\Shared\Transfer\HydrateEventsRequestTransfer $hydrateEventsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HydrateEventsResponseTransfer
     */
    protected function hydrateEventDataTransfer(HydrateEventsRequestTransfer $hydrateEventsRequestTransfer): HydrateEventsResponseTransfer
    {
        return $this->getFactory()->getEventBehaviorFacade()->hydrateEventDataTransfer($hydrateEventsRequestTransfer);
    }
}
