<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Refresher;

use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisherInterface;

class ProductAbstractPageRefresher implements ProductPageRefresherInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisherInterface
     */
    protected $productAbstractPagePublisher;

    /**
     * @var array<\Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractCollectionRefreshPluginInterface>
     */
    protected $productPageRefreshPlugins;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisherInterface $productAbstractPagePublisher
     * @param array<\Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractCollectionRefreshPluginInterface> $productPageRefreshPlugins
     */
    public function __construct(
        ProductAbstractPagePublisherInterface $productAbstractPagePublisher,
        array $productPageRefreshPlugins
    ) {
        $this->productAbstractPagePublisher = $productAbstractPagePublisher;
        $this->productPageRefreshPlugins = $productPageRefreshPlugins;
    }

    /**
     * @return void
     */
    public function refresh(): void
    {
        $productAbstractIds = [];
        foreach ($this->productPageRefreshPlugins as $productPageRefreshPlugin) {
            $productPageLoadTransfer = $productPageRefreshPlugin->getProductPageLoadTransferForRefresh();

            $productAbstractIds = array_merge($productAbstractIds, $productPageLoadTransfer->getProductAbstractIds());
        }

        $this->productAbstractPagePublisher->publish(array_unique($productAbstractIds));
    }
}
