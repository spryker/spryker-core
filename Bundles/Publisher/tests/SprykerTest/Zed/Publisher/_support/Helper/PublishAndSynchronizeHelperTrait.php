<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Publisher\Helper;

trait PublishAndSynchronizeHelperTrait
{
    /**
     * @return \SprykerTest\Zed\Publisher\Helper\PublishAndSynchronizeHelper
     */
    protected function getPublishAndSynchronizeHelper(): PublishAndSynchronizeHelper
    {
        /** @var \SprykerTest\Zed\Publisher\Helper\PublishAndSynchronizeHelper $publishAndSynchronizeHelper */
        $publishAndSynchronizeHelper = $this->getModule('\\' . PublishAndSynchronizeHelper::class);

        return $publishAndSynchronizeHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
