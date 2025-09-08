<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Client\CmsPageSearch\Plugin\Search\SearchHttp\ResultFormatter;

use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\GroupedResultFormatterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

class CmsPageSuggestionsSearchHttpResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface, GroupedResultFormatterPluginInterface
{
    /**
     * @var string
     */
    protected const GROUP_NAME = 'suggestionByType';

    /**
     * @var string
     */
    protected const NAME = 'cms_page';

    /**
     * @var string
     */
    protected const CMS_PAGE_SOURCE_IDENTIFIER = 'cms-page';

    /**
     * @return string
     */
    public function getGroupName(): string
    {
        return static::GROUP_NAME;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     * - Formats cms pages.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return array<int, mixed>
     */
    public function formatResult($searchResult, array $requestParameters = [])
    {
        $formattedResult = [];
        if ($searchResult instanceof SuggestionsSearchHttpResponseTransfer) {
            $formattedResult = $searchResult->getMatchedItemsBySourceIdentifiers()[static::CMS_PAGE_SOURCE_IDENTIFIER] ?? [];
        }

        return $formattedResult;
    }
}
