<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PublishAndSynchronizeHealthCheckSearch\Helper;

use Codeception\TestInterface;
use Orm\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\SpyPublishAndSynchronizeHealthCheckSearchQuery;
use SprykerTest\Shared\Testify\Helper\AbstractHelper;

class PublishAndSynchronizeHealthCheckSearchHelper extends AbstractHelper
{
    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        SpyPublishAndSynchronizeHealthCheckSearchQuery::create()->find()->delete();
    }
}
