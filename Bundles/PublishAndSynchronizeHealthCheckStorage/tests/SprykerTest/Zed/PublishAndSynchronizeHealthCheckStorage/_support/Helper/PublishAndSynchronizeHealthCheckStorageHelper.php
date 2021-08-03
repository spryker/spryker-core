<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PublishAndSynchronizeHealthCheckStorage\Helper;

use Codeception\TestInterface;
use Orm\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\SpyPublishAndSynchronizeHealthCheckStorageQuery;
use SprykerTest\Shared\Testify\Helper\AbstractHelper;

class PublishAndSynchronizeHealthCheckStorageHelper extends AbstractHelper
{
    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        SpyPublishAndSynchronizeHealthCheckStorageQuery::create()->find()->delete();
    }
}
