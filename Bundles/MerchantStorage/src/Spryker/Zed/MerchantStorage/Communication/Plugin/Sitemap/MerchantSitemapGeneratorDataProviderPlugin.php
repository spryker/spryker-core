<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Communication\Plugin\Sitemap;

use Generator;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapGeneratorDataProviderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantStorage\MerchantStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantStorage\Business\MerchantStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantStorage\Communication\MerchantStorageCommunicationFactory getFactory()
 */
class MerchantSitemapGeneratorDataProviderPlugin extends AbstractPlugin implements SitemapGeneratorDataProviderPluginInterface
{
    /**
     * @var string
     */
    protected const MERCHANT_ENTITY_TYPE = 'merchant';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEntityType(): string
    {
        return static::MERCHANT_ENTITY_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Returns an array of sitemap URL related data for merchant.
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
