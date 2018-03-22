<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data\Url;

interface ProductSetUrlDeleterInterface
{
    /**
     * @param int $idProductSet
     *
     * @return void
     */
    public function deleteUrl($idProductSet);
}
