<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementStoreConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductManagementStoreConnector\Business\Model\ProductAbstractStoreRelation\ProductAbstractStoreRelationReader;
use Spryker\Zed\ProductManagementStoreConnector\Business\Model\ProductAbstractStoreRelation\ProductAbstractStoreRelationSaver;

/**
 * @method \Spryker\Zed\ProductManagementStoreConnector\Persistence\ProductManagementStoreConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagementStoreConnector\ProductManagementStoreConnectorConfig getConfig()
 */
class ProductManagementStoreConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductManagementStoreConnector\Business\Model\ProductAbstractStoreRelation\ProductAbstractStoreRelationReaderInterface
     */
    public function createProductAbstractStoreRelationReader()
    {
        return new ProductAbstractStoreRelationReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductManagementStoreConnector\Business\Model\ProductAbstractStoreRelation\ProductAbstractStoreRelationSaverInterface
     */
    public function createProductAbstractStoreRelationSaver()
    {
        return new ProductAbstractStoreRelationSaver(
            $this->getQueryContainer(),
            $this->createProductAbstractStoreRelationReader()
        );
    }
}
