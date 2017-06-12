<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cart\Mapper;

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cart\Mapper;

interface CartItemsMapperInterface
{

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return array
     */
    public function buildMap($items);

}
