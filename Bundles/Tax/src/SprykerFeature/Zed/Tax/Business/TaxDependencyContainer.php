<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Tax\Business;

use SprykerFeature\Zed\Tax\Business\Model\TaxWriter;
use SprykerFeature\Zed\Tax\Business\Model\TaxReader;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Tax\TaxConfig;
use SprykerFeature\Zed\Tax\Business\Model\TaxReaderInterface;
use SprykerFeature\Zed\Tax\Business\Model\TaxWriterInterface;

/**
 * @method TaxConfig getConfig()
 */
class TaxDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return TaxReaderInterface
     */
    public function getReaderModel()
    {
        return new TaxReader(
            $this->getLocator()->tax()->queryContainer()
        );
    }

    /**
     * @return TaxWriterInterface
     */
    public function getWriterModel()
    {
        return new TaxWriter(
            $this->getLocator(),
            $this->getLocator()->tax()->queryContainer(),
            $this->getConfig()->getTaxChangePlugins()
        );
    }

}
