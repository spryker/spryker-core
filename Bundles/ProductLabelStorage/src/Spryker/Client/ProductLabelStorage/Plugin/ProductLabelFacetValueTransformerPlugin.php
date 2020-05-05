<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\FacetSearchResultValueTransformerPluginInterface;

/**
 * @method \Spryker\Client\ProductLabelStorage\ProductLabelStorageFactory getFactory()
 */
class ProductLabelFacetValueTransformerPlugin extends AbstractPlugin implements FacetSearchResultValueTransformerPluginInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function transformForDisplay($value)
    {
        $productLabelDictionaryItemTransfer = $this->getFactory()
            ->createLabelDictionaryReader()
            ->findLabelByIdProductLabel($value, $this->getCurrentLocale(), APPLICATION_STORE);

        if (!$productLabelDictionaryItemTransfer) {
            return $value;
        }

        return $productLabelDictionaryItemTransfer->getName();
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function transformFromDisplay($value)
    {
        $productLabelDictionaryItemTransfer = $this->getFactory()
            ->createLabelDictionaryReader()
            ->findLabelByLocalizedName($value, $this->getCurrentLocale(), APPLICATION_STORE);

        if (!$productLabelDictionaryItemTransfer) {
            return $value;
        }

        return $productLabelDictionaryItemTransfer->getIdProductLabel();
    }

    /**
     * @return string
     */
    protected function getCurrentLocale()
    {
        return $this->getFactory()->getStore()->getCurrentLocale();
    }
}
