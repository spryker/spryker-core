<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Touch\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\TouchBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\Touch\Business\Model\TouchInterface;
use SprykerEngine\Zed\Touch\Business\Model\TouchRecordInterface;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;
use SprykerEngine\Zed\Touch\TouchDependencyProvider;

/**
 * @method TouchBusiness getFactory()
 */
class TouchDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return TouchRecordInterface
     */
    public function getTouchRecordModel()
    {
        return $this->getFactory()->createModelTouchRecord(
            $this->getQueryContainer(),
            $this->getProvidedDependency(TouchDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @return TouchInterface
     */
    public function getTouchModel()
    {
        return $this->getFactory()->createModelTouch(
            $this->getQueryContainer(),
            $this->getProvidedDependency(TouchDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @return TouchQueryContainerInterface
     */
    protected function getQueryContainer()
    {
        return $this->getLocator()->touch()->queryContainer();
    }

}
