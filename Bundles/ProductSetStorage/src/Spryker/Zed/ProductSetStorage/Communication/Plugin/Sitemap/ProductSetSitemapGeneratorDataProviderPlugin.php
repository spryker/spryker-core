<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Communication\Plugin\Sitemap;

use Generator;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapGeneratorDataProviderPluginInterface;

/**
 * @method \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductSetStorage\ProductSetStorageConfig getConfig()
 * @method \Spryker\Zed\ProductSetStorage\Business\ProductSetStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductSetStorage\Communication\ProductSetStorageCommunicationFactory getFactory()
 */
class ProductSetSitemapGeneratorDataProviderPlugin extends AbstractPlugin implements SitemapGeneratorDataProviderPluginInterface
{
    /**
     * @var string
     */
    protected const PRODUCT_SET_ENTITY_TYPE = 'product_set';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEntityType(): string
    {
        return static::PRODUCT_SET_ENTITY_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Returns an array of sitemap URL related data for product sets.
     *
     * @api
     *
     * @param string $storeName
     * @param int $limit
     *
     * @return \Generator
     */
    public function getSitemapUrls(string $storeName, int $limit): Generator
    {
        return $this->getRepository()->getSitemapGeneratorUrls($storeName, $limit);
    }
}
