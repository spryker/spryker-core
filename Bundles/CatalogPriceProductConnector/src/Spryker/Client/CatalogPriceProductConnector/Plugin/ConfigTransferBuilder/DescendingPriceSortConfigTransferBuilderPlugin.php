<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector\Plugin\ConfigTransferBuilder;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\Catalog\Dependency\Plugin\SortConfigTransferBuilderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\CatalogPriceProductConnector\CatalogPriceProductConnectorFactory getFactory()
 */
class DescendingPriceSortConfigTransferBuilderPlugin extends AbstractPlugin implements SortConfigTransferBuilderPluginInterface
{
    public const PARAMETER_NAME = 'price_desc';

    /**
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    public function build()
    {
        $priceIdentifier = $this->getFactory()
            ->createPriceIdentifierBuilder()
            ->buildIdentifierForCurrentCurrency();

        return (new SortConfigTransfer())
            ->setName($priceIdentifier)
            ->setParameterName(static::PARAMETER_NAME)
            ->setFieldName(PageIndexMap::INTEGER_SORT)
            ->setIsDescending(true);
    }
}
