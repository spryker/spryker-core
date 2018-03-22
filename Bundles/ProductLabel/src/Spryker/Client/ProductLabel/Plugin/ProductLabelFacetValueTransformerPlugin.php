<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\FacetSearchResultValueTransformerPluginInterface;

/**
 * @method \Spryker\Client\ProductLabel\ProductLabelFactory getFactory()
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
        $storageProductLabelTransfer = $this->getFactory()
            ->createLabelDictionaryReader()
            ->findLabelByIdProductLabel($value, $this->getCurrentLocale());

        if (!$storageProductLabelTransfer) {
            return $value;
        }

        return $storageProductLabelTransfer->getName();
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function transformFromDisplay($value)
    {
        $storageProductLabelTransfer = $this->getFactory()
            ->createLabelDictionaryReader()
            ->findLabelByLocalizedName($value, $this->getCurrentLocale());

        if (!$storageProductLabelTransfer) {
            return $value;
        }

        return $storageProductLabelTransfer->getIdProductLabel();
    }

    /**
     * @return string
     */
    protected function getCurrentLocale()
    {
        return $this->getFactory()->getStore()->getCurrentLocale();
    }
}
