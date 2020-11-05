<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Communication\Plugin\Publisher\Category;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\MerchantCategory\MerchantCategoryConfig getConfig()
 * @method \Spryker\Zed\MerchantCategory\Business\MerchantCategoryFacadeInterface getFacade()
 */
class CategoryWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * @uses \Spryker\Zed\Category\Dependency\CategoryEvents::ENTITY_SPY_CATEGORY_UPDATE
     */
    public const ENTITY_SPY_CATEGORY_UPDATE = 'Entity.spy_category.update';

    /**
     * {@inheritDoc}
     * - Retrieves all MerchantCategories using foreign keys from $eventTransfers.
     * - Sends `MerchantCategory.merchant_category.publish` event.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName): void
    {
        $this->getFacade()->publishCategoryMerchantEventsByCategoryEvents($transfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            static::ENTITY_SPY_CATEGORY_UPDATE,
        ];
    }
}
