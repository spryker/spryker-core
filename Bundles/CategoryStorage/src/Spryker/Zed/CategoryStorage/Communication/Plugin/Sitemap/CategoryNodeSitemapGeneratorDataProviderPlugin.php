<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Communication\Plugin\Sitemap;

use Generator;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapGeneratorDataProviderPluginInterface;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryStorage\CategoryStorageConfig getConfig()
 * @method \Spryker\Zed\CategoryStorage\Business\CategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryStorage\Communication\CategoryStorageCommunicationFactory getFactory()
 */
class CategoryNodeSitemapGeneratorDataProviderPlugin extends AbstractPlugin implements SitemapGeneratorDataProviderPluginInterface
{
    /**
     * @var string
     */
    protected const CATEGORY_NODE_ENTITY_TYPE = 'category_node';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEntityType(): string
    {
        return static::CATEGORY_NODE_ENTITY_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Returns an array of sitemap URL related data for category nodes.
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
