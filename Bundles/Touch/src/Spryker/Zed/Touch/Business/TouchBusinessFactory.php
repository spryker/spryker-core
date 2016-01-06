<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Touch\Business;

use Spryker\Zed\Touch\Business\Model\Touch;
use Spryker\Zed\Touch\Business\Model\TouchRecord;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Touch\Business\Model\TouchInterface;
use Spryker\Zed\Touch\Business\Model\TouchRecordInterface;
use Spryker\Zed\Touch\TouchDependencyProvider;
use Spryker\Zed\Touch\TouchConfig;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;

/**
 * @method TouchConfig getConfig()
 * @method TouchQueryContainer getQueryContainer()
 */
class TouchBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return TouchRecordInterface
     */
    public function createTouchRecordModel()
    {
        return new TouchRecord(
            $this->getQueryContainer(),
            $this->getProvidedDependency(TouchDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @return TouchInterface
     */
    public function createTouchModel()
    {
        return new Touch(
            $this->getQueryContainer(),
            $this->getProvidedDependency(TouchDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

}
