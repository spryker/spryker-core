<?php

namespace SprykerFeature\Zed\Tax\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Tax\TaxConfig;
use SprykerFeature\Zed\Tax\Business\Model\TaxReaderInterface;
use SprykerFeature\Zed\Tax\Business\Model\TaxWriterInterface;
use Generated\Zed\Ide\FactoryAutoCompletion\TaxBusiness;

/**
 * @method TaxBusiness getFactory()
 * @method TaxConfig getConfig()
 */
class TaxDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return TaxReaderInterface
     */
    public function getReaderModel()
    {
        return $this->getFactory()->createModelTaxReader(
            $this->getLocator()->tax()->queryContainer(),
            $this->getConfig()
        );
    }

    /**
     * @return TaxWriterInterface
     */
    public function getWriterModel()
    {
        return $this->getFactory()->createModelTaxWriter(
            $this->getLocator(),
            $this->getLocator()->tax()->queryContainer(),
            $this->getConfig()
        );
    }
}
