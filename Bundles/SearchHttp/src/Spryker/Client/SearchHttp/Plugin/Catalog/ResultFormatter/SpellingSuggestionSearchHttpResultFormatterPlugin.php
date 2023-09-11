<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

class SpellingSuggestionSearchHttpResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface
{
    /**
     * @var string
     */
    public const NAME = 'spellingSuggestion';

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
     * - Currently not supported by plugin, but necessary for compatibility with Yves functionality.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer $searchResult
     * @param array<string, int> $requestParameters
     *
     * @return array<mixed>
     */
    public function formatResult($searchResult, array $requestParameters = []): array
    {
        return [];
    }
}
