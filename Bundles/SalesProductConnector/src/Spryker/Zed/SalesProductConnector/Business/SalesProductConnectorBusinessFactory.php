<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesProductConnector\Business\Model\ItemMetadataHydrator;
use Spryker\Zed\SalesProductConnector\Business\Model\ItemMetadataSaver;
use Spryker\Zed\SalesProductConnector\Business\Model\ProductIdHydrator;
use Spryker\Zed\SalesProductConnector\SalesProductConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface getQueryContainer()
 */
class SalesProductConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\SalesProductConnector\Business\Model\ItemMetadataSaverInterface
     */
    public function createItemMetadataSaver()
    {
        return new ItemMetadataSaver(
            $this->getUtilEncodingService(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\SalesProductConnector\Business\Model\ItemMetadataHydratorInterface
     */
    public function createItemMetadataHydrator()
    {
        return new ItemMetadataHydrator(
            $this->getUtilEncodingService(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\SalesProductConnector\Business\Model\ProductIdHydratorInterface
     */
    public function createProductIdHydrator()
    {
        return new ProductIdHydrator(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(SalesProductConnectorDependencyProvider::SERVICE_UTIL_ENCODING);
    }

}
