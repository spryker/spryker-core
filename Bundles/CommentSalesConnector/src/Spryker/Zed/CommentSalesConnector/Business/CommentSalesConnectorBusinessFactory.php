<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentSalesConnector\Business;

use Spryker\Zed\CommentSalesConnector\Business\Reader\CommentReader;
use Spryker\Zed\CommentSalesConnector\Business\Reader\CommentReaderInterface;
use Spryker\Zed\CommentSalesConnector\Business\Writer\CommentWriter;
use Spryker\Zed\CommentSalesConnector\Business\Writer\CommentWriterInterface;
use Spryker\Zed\CommentSalesConnector\CommentSalesConnectorDependencyProvider;
use Spryker\Zed\CommentSalesConnector\Dependency\Facade\CommentSalesConnectorToCommentFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CommentSalesConnector\CommentSalesConnectorConfig getConfig()
 */
class CommentSalesConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CommentSalesConnector\Business\Writer\CommentWriterInterface
     */
    public function createCommentWriter(): CommentWriterInterface
    {
        return new CommentWriter(
            $this->getCustomerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CommentSalesConnector\Business\Reader\CommentReaderInterface
     */
    public function createCommentReader(): CommentReaderInterface
    {
        return new CommentReader(
            $this->getCustomerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CommentSalesConnector\Dependency\Facade\CommentSalesConnectorToCommentFacadeInterface
     */
    public function getCustomerFacade(): CommentSalesConnectorToCommentFacadeInterface
    {
        return $this->getProvidedDependency(CommentSalesConnectorDependencyProvider::FACADE_COMMENT);
    }
}
