<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Client\SelfServicePortal\Plugin\Elasticsearch\ResultFormatter;

use Generated\Shared\Transfer\SspAssetSearchCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SspAssetSearchResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface
{
    /**
     * @var string
     */
    protected const NAME = 'ssp-asset-search';

    /**
     * {@inheritDoc}
     * - Formats search ssp asset search result.
     *
     * @api
     *
     * @param \Elastica\ResultSet|mixed $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return \Generated\Shared\Transfer\SspAssetSearchCollectionTransfer
     */
    public function formatResult($searchResult, array $requestParameters = []): SspAssetSearchCollectionTransfer
    {
        return $this->getFactory()->createSspAssetSearchResultFormatter()->formatResult($searchResult, $requestParameters);
    }

    /**
     * {@inheritDoc}
     * - Returns result formatter name.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }
}
