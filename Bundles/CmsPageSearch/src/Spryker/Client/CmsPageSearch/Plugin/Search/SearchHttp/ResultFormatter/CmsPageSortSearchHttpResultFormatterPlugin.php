<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Client\CmsPageSearch\Plugin\Search\SearchHttp\ResultFormatter;

use Generated\Shared\Transfer\SortSearchResultTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

/**
 * @method \Spryker\Client\CmsPageSearch\CmsPageSearchFactory getFactory()
 */
class CmsPageSortSearchHttpResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface
{
    /**
     * @var string
     */
    public const NAME = 'sort';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     * - Formats sort by field and sort direction in result for CMS pages.
     * - Uses CMS page specific sort configuration.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return \Generated\Shared\Transfer\SortSearchResultTransfer
     */
    public function formatResult($searchResult, array $requestParameters = []): SortSearchResultTransfer
    {
        $sortConfig = $this->getFactory()->createSortConfigBuilder();
        $sortParamName = $sortConfig->getActiveParamName($requestParameters);

        return (new SortSearchResultTransfer())
            ->setSortParamNames(array_keys($sortConfig->getAllSortConfigTransfers()))
            ->setCurrentSortParam($sortParamName)
            ->setCurrentSortOrder($sortConfig->getSortDirection($sortParamName));
    }
}
