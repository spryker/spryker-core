<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Validator;

/**
 * Interface DataValidatorInterface
 *
 * @package SprykerFeature\Zed\Product\Business\Validator
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