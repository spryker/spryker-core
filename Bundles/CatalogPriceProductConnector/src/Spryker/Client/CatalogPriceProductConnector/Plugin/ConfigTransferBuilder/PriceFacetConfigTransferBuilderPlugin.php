<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector\Plugin\ConfigTransferBuilder;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Catalog\Dependency\Plugin\FacetConfigTransferBuilderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\Search\SearchConfig;

/**
 * @method \Spryker\Client\CatalogPriceProductConnector\CatalogPriceProductConnectorFactory getFactory()
 */
class PriceFacetConfigTransferBuilderPlugin extends AbstractPlugin implements FacetConfigTransferBuilderPluginInterface
{
    public const PARAMETER_NAME = 'price';

    /**
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    public function build()
    {
        $priceIdentifier = $this->getFactory()
            ->createPriceIdentifierBuilder()
            ->buildIdentifierForCurrentCurrency();

        return (new FacetConfigTransfer())
            ->setName($priceIdentifier)
            ->setParameterName(static::PARAMETER_NAME)
            ->setFieldName(PageIndexMap::INTEGER_FACET)
            ->setType(SearchConfig::FACET_TYPE_PRICE_RANGE);
    }
}
