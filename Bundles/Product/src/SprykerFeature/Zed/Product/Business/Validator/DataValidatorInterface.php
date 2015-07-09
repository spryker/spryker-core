<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Validator;

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
