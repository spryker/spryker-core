<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Config;

use Generated\Shared\Transfer\PaginationConfigTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Config\PaginationConfigBuilderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\PaginationSearchConfigBuilderPluginInterface;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 */
class PaginationConfigBuilderPlugin extends AbstractPlugin implements PaginationSearchConfigBuilderPluginInterface
{
    public const DEFAULT_ITEMS_PER_PAGE = 12;
    public const VALID_ITEMS_PER_PAGE_OPTIONS = [12, 24, 36];
    public const PARAMETER_NAME_PAGE = 'page';
    public const PARAMETER_NAME_ITEMS_PER_PAGE = 'ipp';

    /**
     * {@inheritdoc}
     * - Adds catalog search specific pagination configuration.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Config\PaginationConfigBuilderInterface $paginationConfigBuilder
     *
     * @return void
     */
    public function buildPaginationConfig(PaginationConfigBuilderInterface $paginationConfigBuilder)
    {
        $paginationConfigTransfer = (new PaginationConfigTransfer())
            ->setParameterName(static::PARAMETER_NAME_PAGE)
            ->setItemsPerPageParameterName(static::PARAMETER_NAME_ITEMS_PER_PAGE)
            ->setDefaultItemsPerPage(static::DEFAULT_ITEMS_PER_PAGE)
            ->setValidItemsPerPageOptions(static::VALID_ITEMS_PER_PAGE_OPTIONS);

        $paginationConfigBuilder->setPagination($paginationConfigTransfer);
    }
}
