<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business\Attribute;

interface AttributeMapInterface
{
    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array
     */
    public function generateAttributeMap($idProductAbstract, $idLocale);
}
