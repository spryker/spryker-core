<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Model\Validator;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ProductApiTransfer;

class ProductApiValidator implements ProductApiValidatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return array
     */
    public function validate(ApiDataTransfer $apiDataTransfer)
    {
        $t = new ProductApiTransfer();
        die('validating');
    }

}
