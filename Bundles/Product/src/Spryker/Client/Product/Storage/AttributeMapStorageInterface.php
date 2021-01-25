<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product\Storage;

interface AttributeMapStorageInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getAttributeMapByIdProductAbstract($idProductAbstract);
}
