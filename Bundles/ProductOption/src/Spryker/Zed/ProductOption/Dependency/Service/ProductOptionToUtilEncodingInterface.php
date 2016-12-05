<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Dependency\Service;

interface ProductOptionToUtilEncodingInterface
{

    /**
     * @param string $jsonValue
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string
     */
    public function encodeJson($jsonValue, $options = null, $depth = null);

}
