<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PublishAndSynchronizeHealthCheck\Helper;

use Codeception\TestInterface;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacadeInterface;
use SprykerTest\Shared\Testify\Helper\AbstractHelper;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;

class PublishAndSynchronizeHealthCheckDataHelper extends AbstractHelper
{
    use BusinessHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        SpyPublishAndSynchronizeHealthCheckQuery::create()->find()->delete();
    }

    /**
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer
     */
    public function havePublishAndSynchronizeHealthCheck(): PublishAndSynchronizeHealthCheckTransfer
    {
        $publishAndSynchronizeHealthCheckTransfer = $this->getFacade()->savePublishAndSynchronizeHealthCheckEntity();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($publishAndSynchronizeHealthCheckTransfer): void {
            $publishAndSynchronizeHealthCheckEntity = SpyPublishAndSynchronizeHealthCheckQuery::create()->findOneByHealthCheckKey(
                $publishAndSynchronizeHealthCheckTransfer->getHealthCheckKey(),
            );

            if ($publishAndSynchronizeHealthCheckEntity !== null) {
                $publishAndSynchronizeHealthCheckEntity->delete();
            }
        });

        return $publishAndSynchronizeHealthCheckTransfer;
    }

    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacadeInterface
     */
    protected function getFacade(): PublishAndSynchronizeHealthCheckFacadeInterface
    {
        /** @var \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacadeInterface $publishAndSynchronizeHealthCheckFacade */
        $publishAndSynchronizeHealthCheckFacade = $this->getBusinessHelper()->getFacade('PublishAndSynchronizeHealthCheck');

        return $publishAndSynchronizeHealthCheckFacade;
    }
}
