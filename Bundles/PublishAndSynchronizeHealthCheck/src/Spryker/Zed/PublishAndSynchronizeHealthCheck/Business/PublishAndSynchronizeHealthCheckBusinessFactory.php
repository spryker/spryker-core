<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\Reader\DataReader;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\Reader\DataReaderInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\Writer\DataWriter;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\Writer\DataWriterInterface;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheck\PublishAndSynchronizeHealthCheckConfig getConfig()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence\PublishAndSynchronizeHealthCheckEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence\PublishAndSynchronizeHealthCheckRepositoryInterface getRepository()
 */
class PublishAndSynchronizeHealthCheckBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\Reader\DataReaderInterface
     */
    public function createPublishAndSynchronizeHealthCheckDataReader(): DataReaderInterface
    {
        return new DataReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\Writer\DataWriterInterface
     */
    public function createPublishAndSynchronizeHealthCheckDataWriter(): DataWriterInterface
    {
        return new DataWriter($this->getEntityManager());
    }
}
