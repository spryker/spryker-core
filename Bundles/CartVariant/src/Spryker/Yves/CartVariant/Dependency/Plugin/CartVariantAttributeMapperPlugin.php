<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CartVariant\Dependency\Plugin;

use ArrayObject;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\CartVariant\CartVariantFactory getFactory()
 */
class CartVariantAttributeMapperPlugin extends AbstractPlugin implements CartVariantAttributeMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject $items
     *
     * @return array
     */
    public function buildMap(ArrayObject $items)
    {
        return $this->getFactory()->createCartItemsAttributeMapper()->buildMap($items);
    }
}
