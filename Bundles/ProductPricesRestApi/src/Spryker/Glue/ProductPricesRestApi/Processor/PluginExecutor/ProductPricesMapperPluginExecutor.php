<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\PluginExecutor;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\RestProductPricesAttributesTransfer;

class ProductPricesMapperPluginExecutor implements ProductPricesMapperPluginExecutorInterface
{
    /**
     * @var \Spryker\Glue\ProductPricesRestApiExtension\Dependency\Plugin\RestProductPricesAttributesMapperPluginInterface[]
     */
    protected $restProductPricesAttributesMapperPlugins;

    /**
     * @param \Spryker\Glue\ProductPricesRestApiExtension\Dependency\Plugin\RestProductPricesAttributesMapperPluginInterface[] $restProductPricesAttributesMapperPlugins
     */
    public function __construct(array $restProductPricesAttributesMapperPlugins)
    {
        $this->restProductPricesAttributesMapperPlugins = $restProductPricesAttributesMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\RestProductPricesAttributesTransfer $restProductPriceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPricesAttributesTransfer
     */
    public function mapRestProductPricesAttributes(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        RestProductPricesAttributesTransfer $restProductPriceAttributesTransfer
    ): RestProductPricesAttributesTransfer {
        foreach ($this->restProductPricesAttributesMapperPlugins as $restProductPricesAttributesMapperPlugin) {
            $restProductPriceAttributesTransfer = $restProductPricesAttributesMapperPlugin->map(
                $currentProductPriceTransfer,
                $restProductPriceAttributesTransfer
            );
        }

        return $restProductPriceAttributesTransfer;
    }
}
