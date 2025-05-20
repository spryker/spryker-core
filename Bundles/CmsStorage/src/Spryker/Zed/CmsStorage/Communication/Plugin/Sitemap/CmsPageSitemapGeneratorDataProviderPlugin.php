<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Communication\Plugin\Sitemap;

use Generator;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapGeneratorDataProviderPluginInterface;

/**
 * @method \Spryker\Zed\CmsStorage\Persistence\CmsStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CmsStorage\CmsStorageConfig getConfig()
 * @method \Spryker\Zed\CmsStorage\Business\CmsStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsStorage\Communication\CmsStorageCommunicationFactory getFactory()
 */
class CmsPageSitemapGeneratorDataProviderPlugin extends AbstractPlugin implements SitemapGeneratorDataProviderPluginInterface
{
    /**
     * @var string
     */
    protected const PAGE_ENTITY_TYPE = 'page';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEntityType(): string
    {
        return static::PAGE_ENTITY_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Returns an array of sitemap URL related data for cms pages.
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
