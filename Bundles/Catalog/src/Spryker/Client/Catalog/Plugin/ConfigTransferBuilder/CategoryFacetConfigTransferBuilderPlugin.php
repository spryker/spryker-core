<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\ConfigTransferBuilder;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Catalog\Dependency\Plugin\FacetConfigTransferBuilderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Model\Elasticsearch\Aggregation\CategoryFacetAggregation;
use Spryker\Shared\Search\SearchConfig;

class CategoryFacetConfigTransferBuilderPlugin extends AbstractPlugin implements FacetConfigTransferBuilderPluginInterface
{
    public const NAME = 'category';
    public const PARAMETER_NAME = 'category';
    public const SIZE = 1000;

    /**
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    public function build()
    {
        return (new FacetConfigTransfer())
            ->setName(static::NAME)
            ->setParameterName(static::PARAMETER_NAME)
            ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
            ->setType(SearchConfig::FACET_TYPE_CATEGORY)
            ->setAggregationParams([
                CategoryFacetAggregation::AGGREGATION_PARAM_SIZE => static::SIZE,
            ]);
    }
}
