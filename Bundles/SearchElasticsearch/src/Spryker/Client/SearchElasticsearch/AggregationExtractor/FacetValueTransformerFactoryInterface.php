<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\FacetSearchResultValueTransformerPluginInterface;

interface FacetValueTransformerFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\FacetSearchResultValueTransformerPluginInterface|null
     */
    public function createTransformer(FacetConfigTransfer $facetConfigTransfer): ?FacetSearchResultValueTransformerPluginInterface;
}
