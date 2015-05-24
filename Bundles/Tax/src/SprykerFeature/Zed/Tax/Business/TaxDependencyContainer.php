<?php

namespace SprykerFeature\Zed\Tax\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Tax\TaxConfig;
use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainerInterface;
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
     * @var TaxQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @return TaxReaderInterface
     */
    public function getReaderModel()
    {
        return $this->getFactory()->createModelTaxReader(
            $this->getQueryContainer(),
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
            $this->getQueryContainer(),
            $this->getConfig()
        );
    }

    /**
     * @return TaxQueryContainerInterface
     */
    protected function getQueryContainer()
    {
        if (empty($this->queryContainer)) {
            $this->queryContainer = $this->getLocator()->tax()->queryContainer();
        }

        return $this->queryContainer;
    }
}
