<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Model\Validator;

use Generated\Shared\Transfer\ApiDataTransfer;

interface CustomerApiValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @throws \Spryker\Zed\Api\Business\Exception\ApiValidationException
     *
     * @return array
     */
    public function validate(ApiDataTransfer $apiDataTransfer);
}
