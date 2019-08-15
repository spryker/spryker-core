<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Touch\Business\Model\BulkTouch\BulkTouch;
use Spryker\Zed\Touch\Business\Model\BulkTouch\Filter\IdFilterInsert;
use Spryker\Zed\Touch\Business\Model\BulkTouch\Filter\IdFilterUpdate;
use Spryker\Zed\Touch\Business\Model\BulkTouch\Handler\BulkTouchHandlerInsert;
use Spryker\Zed\Touch\Business\Model\BulkTouch\Handler\BulkTouchHandlerUpdate;
use Spryker\Zed\Touch\Business\Model\Touch;
use Spryker\Zed\Touch\Business\Model\TouchRecord;
use Spryker\Zed\Touch\TouchDependencyProvider;

/**
 * @method \Spryker\Zed\Touch\TouchConfig getConfig()
 * @method \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Touch\Persistence\TouchEntityManagerInterface getEntityManager()
 */
class TouchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Touch\Business\Model\TouchRecordInterface
     */
    public function createTouchRecordModel()
    {
        return new TouchRecord(
            $this->getUtilDataReaderService(),
            $this->getQueryContainer(),
            $this->getProvidedDependency(TouchDependencyProvider::PLUGIN_PROPEL_CONNECTION),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(TouchDependencyProvider::SERVICE_DATA);
    }

    /**
     * @return \Spryker\Zed\Touch\Business\Model\TouchInterface
     */
    public function createTouchModel()
    {
        return new Touch(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Touch\Business\Model\BulkTouch\BulkTouchInterface
     */
    public function createBulkTouchModel()
    {
        $bulkTouchHandler = $this->createBulkTouchHandler();

        return new BulkTouch($bulkTouchHandler);
    }

    /**
     * @return \Spryker\Zed\Touch\Business\Model\BulkTouch\BulkTouchInterface[]
     */
    protected function createBulkTouchHandler()
    {
        return [
            $this->createBulkTouchHandlerUpdate(),
            $this->createBulkTouchHandlerInsert(),
        ];
    }

    /**
     * @return \Spryker\Zed\Touch\Business\Model\BulkTouch\Handler\BulkTouchHandlerInsert
     */
    protected function createBulkTouchHandlerInsert()
    {
        return new BulkTouchHandlerInsert($this->getQueryContainer(), $this->createIdFilterInsert());
    }

    /**
     * @return \Spryker\Zed\Touch\Business\Model\BulkTouch\Filter\IdFilterInsert
     */
    protected function createIdFilterInsert()
    {
        return new IdFilterInsert($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Touch\Business\Model\BulkTouch\Handler\BulkTouchHandlerUpdate
     */
    protected function createBulkTouchHandlerUpdate()
    {
        return new BulkTouchHandlerUpdate($this->getQueryContainer(), $this->createIdFilterUpdate());
    }

    /**
     * @return \Spryker\Zed\Touch\Business\Model\BulkTouch\Filter\IdFilterUpdate
     */
    protected function createIdFilterUpdate()
    {
        return new IdFilterUpdate($this->getQueryContainer());
    }
}
