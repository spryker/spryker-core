<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Importer\Validator;

use Spryker\Zed\Product\Business\Validator\DataValidatorInterface;

class ImportProductValidator implements DataValidatorInterface
{

    /**
     * @param array $data
     *
     * @return bool
     */
    public function isValid(array $data)
    {
        return (
            $this->hasValidName($data) &&
            $this->hasValidSku($data)
        );
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    protected function hasValidName(array $data)
    {
        return (isset($data['name']));
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    protected function hasValidSku(array $data)
    {
        return (isset($data['sku']));
    }

}
