<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Validator;

use Generated\Shared\Transfer\ApiRequestTransfer;

interface ValidatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return array
     */
    public function validate(ApiRequestTransfer $apiRequestTransfer);

}
