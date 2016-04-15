<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
