<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Plugin\Catalog;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Catalog\Dependency\Plugin\FacetConfigTransferBuilderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\Search\SearchConfig;

class ProductClassFacetConfigTransferBuilderPlugin extends AbstractPlugin implements FacetConfigTransferBuilderPluginInterface
{
    /**
     * @var string
     */
    protected const NAME = 'product-class-names';

    /**
     * @var string
     */
    protected const PARAMETER_NAME = 'product-class-names';

    /**
     * {@inheritDoc}
     * - Builds a facet filter configuration transfer for product classes.
     * - Configures the facet as an enumeration type with multi-value support.
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    public function build(): FacetConfigTransfer
    {
        return (new FacetConfigTransfer())
            ->setName(static::NAME)
            ->setParameterName(static::PARAMETER_NAME)
            ->setFieldName(PageIndexMap::STRING_FACET)
            ->setType(SearchConfig::FACET_TYPE_ENUMERATION)
            ->setIsMultiValued(true);
    }
}
