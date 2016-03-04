<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Validator;

/**
 * Interface DataValidatorInterface
 */

interface DataValidatorInterface
{

    /**
     * @param array $data
     *
     * @return bool
     */
    public function isValid(array $data);

}
