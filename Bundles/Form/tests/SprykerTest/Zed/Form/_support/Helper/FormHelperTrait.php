<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Form\Helper;

trait FormHelperTrait
{
    /**
     * @return \SprykerTest\Zed\Form\Helper\FormHelper
     */
    protected function getFormHelper(): FormHelper
    {
        /** @var \SprykerTest\Zed\Form\Helper\FormHelper $formHelper */
        $formHelper = $this->getModule('\\' . FormHelper::class);

        return $formHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
