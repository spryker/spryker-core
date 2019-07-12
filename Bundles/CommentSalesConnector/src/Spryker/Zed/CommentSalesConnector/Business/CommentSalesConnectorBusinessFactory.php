<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentSalesConnector\Business;

use Spryker\Zed\CommentSalesConnector\Business\Reader\CommentThreadReader;
use Spryker\Zed\CommentSalesConnector\Business\Reader\CommentThreadReaderInterface;
use Spryker\Zed\CommentSalesConnector\Business\Writer\CommentThreadWriter;
use Spryker\Zed\CommentSalesConnector\Business\Writer\CommentThreadWriterInterface;
use Spryker\Zed\CommentSalesConnector\CommentSalesConnectorDependencyProvider;
use Spryker\Zed\CommentSalesConnector\Dependency\Facade\CommentSalesConnectorToCommentFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CommentSalesConnector\CommentSalesConnectorConfig getConfig()
 */
class CommentSalesConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CommentSalesConnector\Business\Writer\CommentThreadWriterInterface
     */
    public function createCommentThreadWriter(): CommentThreadWriterInterface
    {
        return new CommentThreadWriter(
            $this->getCustomerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CommentSalesConnector\Business\Reader\CommentThreadReaderInterface
     */
    public function createCommentThreadReader(): CommentThreadReaderInterface
    {
        return new CommentThreadReader(
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
