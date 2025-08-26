<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Client\SelfServicePortal\Plugin\Catalog;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspAssetQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands search query with asset-specific product filtering based on SSP asset reference.
     * - Requires 'ssp-asset-reference' parameter in request parameters.
     * - Validates current company user exists.
     * - Retrieves SSP asset storage by reference and company user.
     * - Retrieves SSP model storage transfers by model IDs.
     * - Extracts product whitelist IDs from the models.
     * - Applies whitelist filter to restrict search results to products in those whitelists.
     * - Applies no-results filter if any validation step fails or no whitelists are found.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        return $this->getFactory()
            ->createSspAssetQueryExpander()
            ->expandQuery($searchQuery, $requestParameters);
    }
}
