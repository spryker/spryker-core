<?php

namespace SprykerFeature\Zed\Tax\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\TaxBusiness;
use SprykerFeature\Zed\Tax\TaxConfig;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainer;
use SprykerFeature\Zed\Tax\Business\Model\TaxReaderInterface;
use SprykerFeature\Zed\Tax\Business\Model\TaxWriterInterface;

/**
 * @method TaxBusiness getFactory()
 * @method TaxConfig getConfig()
 */
class TaxDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @var TaxQueryContainer
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
            $this->getReaderModel(),
            $this->getConfig()
        );
    }

    /**
     * @return TaxQueryContainer
     */
    protected function getQueryContainer()
    {
        if (empty($this->queryContainer)) {
            $this->queryContainer = $this->getLocator()->tax()->queryContainer();
        }

        return $this->queryContainer;
    }
}
