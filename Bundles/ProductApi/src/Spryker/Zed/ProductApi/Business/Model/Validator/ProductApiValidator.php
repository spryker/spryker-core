<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Model\Validator;

use Exception;
use Generated\Shared\Transfer\ApiDataTransfer;

class ProductApiValidator implements ProductApiValidatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @throws \Exception
     *
     * @return array
     */
    public function validate(ApiDataTransfer $apiDataTransfer)
    {
        throw new Exception('Implement similar to CustomerApi');
    }

}
