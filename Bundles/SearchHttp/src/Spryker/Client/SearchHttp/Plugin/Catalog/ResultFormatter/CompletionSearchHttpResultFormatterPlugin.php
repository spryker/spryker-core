<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 */
class CompletionSearchHttpResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface
{
    /**
     * @var string
     */
    public const NAME = 'completion';

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
     * - Formats completions in result.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function formatResult($searchResult, array $requestParameters = []): array
    {
        return $searchResult->getCompletions();
    }
}
