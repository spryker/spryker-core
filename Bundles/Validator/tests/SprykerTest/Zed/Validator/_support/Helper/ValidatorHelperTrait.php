<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Validator\Helper;

trait ValidatorHelperTrait
{
    /**
     * @return \SprykerTest\Zed\Validator\Helper\ValidatorHelper
     */
    protected function getValidatorHelper(): ValidatorHelper
    {
        /** @var \SprykerTest\Zed\Validator\Helper\ValidatorHelper $validatorHelper */
        $validatorHelper = $this->getModule('\\' . ValidatorHelper::class);

        return $validatorHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
