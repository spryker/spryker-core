<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Exception;

use RuntimeException;

class ProductNotFoundException extends RuntimeException
{

    public function __construct($id)
    {
        parent::__construct('The product was not found' . PHP_EOL . '[id] ' . $id);
    }

}
