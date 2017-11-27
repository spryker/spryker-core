<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Hydrator;

interface HydratorInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transferObject
     * @param string|array $data
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function hydrate($transferObject, $data);
}
