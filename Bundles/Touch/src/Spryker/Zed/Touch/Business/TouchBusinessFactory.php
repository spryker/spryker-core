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

class TouchBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return TouchRecordInterface
     */
    public function getTouchRecordModel()
    {
        return new TouchRecord(
            $this->getQueryContainer(),
            $this->getProvidedDependency(TouchDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @return TouchInterface
     */
    public function getTouchModel()
    {
        return new Touch(
            $this->getQueryContainer(),
            $this->getProvidedDependency(TouchDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

}
