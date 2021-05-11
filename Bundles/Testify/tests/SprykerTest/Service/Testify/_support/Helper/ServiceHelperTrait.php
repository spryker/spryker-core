<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Testify\Helper;

trait ServiceHelperTrait
{
    /**
     * @return \SprykerTest\Service\Testify\Helper\ServiceHelper
     */
    protected function getServiceHelper(): ServiceHelper
    {
        /** @var \SprykerTest\Service\Testify\Helper\ServiceHelper $serviceHelper */
        $serviceHelper = $this->getModule('\\' . ServiceHelper::class);

        return $serviceHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
