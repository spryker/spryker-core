<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentUserConnector\Business;

use Spryker\Zed\CommentUserConnector\Business\Expander\CommentExpander;
use Spryker\Zed\CommentUserConnector\Business\Expander\CommentExpanderInterface;
use Spryker\Zed\CommentUserConnector\Business\Reader\UserReader;
use Spryker\Zed\CommentUserConnector\Business\Reader\UserReaderInterface;
use Spryker\Zed\CommentUserConnector\Business\Validator\CommentValidator;
use Spryker\Zed\CommentUserConnector\Business\Validator\CommentValidatorInterface;
use Spryker\Zed\CommentUserConnector\CommentUserConnectorDependencyProvider;
use Spryker\Zed\CommentUserConnector\Dependency\Facade\CommentUserConnectorToUserFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CommentUserConnector\CommentUserConnectorConfig getConfig()
 * @method \Spryker\Zed\CommentUserConnector\Persistence\CommentUserConnectorRepositoryInterface getRepository()
 */
class CommentUserConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CommentUserConnector\Business\Expander\CommentExpanderInterface
     */
    public function createCommentExpander(): CommentExpanderInterface
    {
        return new CommentExpander($this->createUserReader());
    }

    /**
     * @return \Spryker\Zed\CommentUserConnector\Business\Validator\CommentValidatorInterface
     */
    public function createCommentValidator(): CommentValidatorInterface
    {
        return new CommentValidator(
            $this->createUserReader(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\CommentUserConnector\Business\Reader\UserReaderInterface
     */
    public function createUserReader(): UserReaderInterface
    {
        return new UserReader($this->getUserFacade());
    }

    /**
     * @return \Spryker\Zed\CommentUserConnector\Dependency\Facade\CommentUserConnectorToUserFacadeInterface
     */
    public function getUserFacade(): CommentUserConnectorToUserFacadeInterface
    {
        return $this->getProvidedDependency(CommentUserConnectorDependencyProvider::FACADE_USER);
    }
}
