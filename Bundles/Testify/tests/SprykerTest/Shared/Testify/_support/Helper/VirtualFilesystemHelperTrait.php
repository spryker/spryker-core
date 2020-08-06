<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

trait VirtualFilesystemHelperTrait
{
    /**
     * @return \SprykerTest\Shared\Testify\Helper\VirtualFilesystemHelper
     */
    private function getVirtualFilesystemHelper(): VirtualFilesystemHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\VirtualFilesystemHelper $virtualFilesystemHelper */
        $virtualFilesystemHelper = $this->getModule('\\' . VirtualFilesystemHelper::class);

        return $virtualFilesystemHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
