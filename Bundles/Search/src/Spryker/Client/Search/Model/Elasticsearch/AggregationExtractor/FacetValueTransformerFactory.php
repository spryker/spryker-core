<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\Dependency\Plugin\FacetSearchResultValueTransformerPluginInterface;
use Spryker\Client\Search\Exception\InvalidFacetSearchResultValueTransformerPluginException;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\AggregationExtractor\FacetValueTransformerFactory` instead.
 */
class FacetValueTransformerFactory implements FacetValueTransformerFactoryInterface
{
    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\FacetSearchResultValueTransformerPluginInterface
     */
    protected $valueTransformerPlugin;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @throws \Spryker\Client\Search\Exception\InvalidFacetSearchResultValueTransformerPluginException
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\FacetSearchResultValueTransformerPluginInterface|null
     */
    public function createTransformer(FacetConfigTransfer $facetConfigTransfer)
    {
        $pluginClassName = $facetConfigTransfer->getValueTransformer();
        if (!$pluginClassName) {
            return null;
        }

        $valueTransformerPlugin = new $pluginClassName();

        if (!$valueTransformerPlugin instanceof FacetSearchResultValueTransformerPluginInterface) {
            throw new InvalidFacetSearchResultValueTransformerPluginException(sprintf(
                'Class of "%s" is not a valid implementation of expected %s.',
                $pluginClassName,
                FacetSearchResultValueTransformerPluginInterface::class
            ));
        }

        return $valueTransformerPlugin;
    }
}
