<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferSearch\Plugin;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Catalog\Dependency\Plugin\FacetConfigTransferBuilderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\Search\SearchConfig;

class MerchantNameFacetConfigTransferBuilderPlugin extends AbstractPlugin implements FacetConfigTransferBuilderPluginInterface
{
    public const NAME = 'merchant_name';
    public const PARAMETER_NAME = 'merchant_name';

    /**
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    public function build()
    {
        return (new FacetConfigTransfer())
            ->setName(static::NAME)
            ->setParameterName(static::PARAMETER_NAME)
            ->setFieldName(PageIndexMap::STRING_FACET)
            ->setType(SearchConfig::FACET_TYPE_ENUMERATION)
            ->setIsMultiValued(true);
    }
}
