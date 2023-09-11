<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Plugin\Catalog\ResultFormatter;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\GroupedResultFormatterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

/**
 * @method \Spryker\Client\CategoryStorage\CategoryStorageClient getClient()
 * @method \Spryker\Client\CategoryStorage\CategoryStorageFactory getFactory()
 */
class CategorySuggestionsSearchHttpResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface, GroupedResultFormatterPluginInterface
{
    /**
     * @var string
     */
    protected const NAME = 'category';

    /**
     * @var string
     */
    protected const GROUP_NAME = 'suggestionByType';

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
     *
     * @api
     *
     * @return string
     */
    public function getGroupName(): string
    {
        return static::GROUP_NAME;
    }

    /**
     * {@inheritDoc}
     * - Formats categories in result.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return array<int, mixed>
     */
    public function formatResult($searchResult, array $requestParameters = []): array
    {
        return $this->getClient()->formatSuggestionsSearchHttpCategory($searchResult);
    }
}
