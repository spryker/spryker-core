<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Tax\Business;

use Spryker\Zed\Tax\Business\Model\TaxWriter;
use Spryker\Zed\Tax\Business\Model\TaxReader;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface;
use Spryker\Zed\Tax\TaxConfig;
use Spryker\Zed\Tax\Business\Model\TaxReaderInterface;
use Spryker\Zed\Tax\Business\Model\TaxWriterInterface;

/**
 * @method TaxConfig getConfig()
 */
class TaxBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return TaxReaderInterface
     */
    public function getReaderModel()
    {
        return new TaxReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return TaxWriterInterface
     */
    public function getWriterModel()
    {
        return new TaxWriter(
            $this->getQueryContainer(),
            $this->getTaxChangePlugins()
        );
    }

    /**
     * @return TaxChangePluginInterface[]
     */
    public function getTaxChangePlugins()
    {
        return [];
    }

}
