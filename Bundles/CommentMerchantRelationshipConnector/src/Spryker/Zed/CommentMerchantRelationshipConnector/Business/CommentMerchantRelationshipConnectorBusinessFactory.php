<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationshipConnector\Business;

use Spryker\Zed\CommentMerchantRelationshipConnector\Business\Expander\CommentThreadExpander;
use Spryker\Zed\CommentMerchantRelationshipConnector\Business\Expander\CommentThreadExpanderInterface;
use Spryker\Zed\CommentMerchantRelationshipConnector\Business\Reader\CommentReader;
use Spryker\Zed\CommentMerchantRelationshipConnector\Business\Reader\CommentReaderInterface;
use Spryker\Zed\CommentMerchantRelationshipConnector\CommentMerchantRelationshipConnectorDependencyProvider;
use Spryker\Zed\CommentMerchantRelationshipConnector\Dependency\Facade\CommentMerchantRelationshipConnectorToCommentFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CommentMerchantRelationshipConnector\CommentMerchantRelationshipConnectorConfig getConfig()
 */
class CommentMerchantRelationshipConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CommentMerchantRelationshipConnector\Business\Expander\CommentThreadExpanderInterface
     */
    public function createCommentThreadExpander(): CommentThreadExpanderInterface
    {
        return new CommentThreadExpander(
            $this->createCommentReader(),
        );
    }

    /**
     * @return \Spryker\Zed\CommentMerchantRelationshipConnector\Business\Reader\CommentReaderInterface
     */
    public function createCommentReader(): CommentReaderInterface
    {
        return new CommentReader(
            $this->getCommentFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CommentMerchantRelationshipConnector\Dependency\Facade\CommentMerchantRelationshipConnectorToCommentFacadeInterface
     */
    public function getCommentFacade(): CommentMerchantRelationshipConnectorToCommentFacadeInterface
    {
        return $this->getProvidedDependency(CommentMerchantRelationshipConnectorDependencyProvider::FACADE_COMMENT);
    }
}
