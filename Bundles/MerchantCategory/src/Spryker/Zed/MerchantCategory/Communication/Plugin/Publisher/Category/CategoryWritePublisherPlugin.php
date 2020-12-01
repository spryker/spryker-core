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
     * @uses \Spryker\Zed\Category\Dependency\CategoryEvents::CATEGORY_AFTER_PUBLISH_UPDATE
     */
    public const CATEGORY_AFTER_PUBLISH_UPDATE = 'Category.after.publish_update';

    /**
     * {@inheritDoc}
     * - Retrieves all MerchantCategories using foreign keys from $eventTransfers.
     * - Sends `MerchantCategory.merchant_category.publish` event.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->publishMerchantCategoryEventsByCategoryEvents($eventEntityTransfers);
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
            static::CATEGORY_AFTER_PUBLISH_UPDATE,
        ];
    }
}
