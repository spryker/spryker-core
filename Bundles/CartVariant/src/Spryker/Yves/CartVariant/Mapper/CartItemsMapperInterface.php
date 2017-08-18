<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CartVariant\Mapper;

use ArrayObject;

interface CartItemsMapperInterface
{

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject $items
     *
     * @return array
     */
    public function buildMap(ArrayObject $items);

}
