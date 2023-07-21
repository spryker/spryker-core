<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Asset\Helper;

use Codeception\Module;

trait AssetDataHelperTrait
{
    /**
     * @return \SprykerTest\Shared\Asset\Helper\AssetDataHelper
     */
    protected function getAssetDataHelper(): AssetDataHelper
    {
        /** @var \SprykerTest\Shared\Asset\Helper\AssetDataHelper $assetDataHelper */
        $assetDataHelper = $this->getModule('\\' . AssetDataHelper::class);

        return $assetDataHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
