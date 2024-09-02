<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Communication\Plugin\ProductAttribute;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductAttributeExtension\Dependency\Plugin\ProductAttributeDataFormatterPluginInterface;

/**
 * @method \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAttribute\Communication\ProductAttributeCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface getQueryContainer()
 */
class MultiSelectProductAttributeDataFormatterPlugin extends AbstractPlugin implements ProductAttributeDataFormatterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Formats product attributes with input type `multiselect` to array.
     *
     * @api
     *
     * @param array<mixed> $attributes
     * @param array<mixed> $formattedAttributes
     *
     * @return array<mixed>
     */
    public function format(array $attributes, array $formattedAttributes): array
    {
        return $this->getFactory()->createMultiSelectAttributeFormatter()->format($attributes, $formattedAttributes);
    }
}
