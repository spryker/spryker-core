<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Communication\Plugin\Sitemap;

use Generator;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapGeneratorDataProviderPluginInterface;

/**
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductStorage\ProductStorageConfig getConfig()
 * @method \Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductStorage\Communication\ProductStorageCommunicationFactory getFactory()
 */
class ProductAbstractSitemapGeneratorDataProviderPlugin extends AbstractPlugin implements SitemapGeneratorDataProviderPluginInterface
{
    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_ENTITY_TYPE = 'product_abstract';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEntityType(): string
    {
        return static::PRODUCT_ABSTRACT_ENTITY_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Returns an array of sitemap URL related data for abstract products.
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
