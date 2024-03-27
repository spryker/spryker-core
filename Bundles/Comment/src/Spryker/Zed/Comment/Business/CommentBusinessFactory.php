<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business;

use Spryker\Zed\Comment\Business\Reader\CommentThreadReader;
use Spryker\Zed\Comment\Business\Reader\CommentThreadReaderInterface;
use Spryker\Zed\Comment\Business\Validator\CommentValidator;
use Spryker\Zed\Comment\Business\Validator\CommentValidatorInterface;
use Spryker\Zed\Comment\Business\Validator\CustomerCommentValidator;
use Spryker\Zed\Comment\Business\Validator\CustomerCommentValidatorInterface;
use Spryker\Zed\Comment\Business\Writer\CommentTagWriter;
use Spryker\Zed\Comment\Business\Writer\CommentTagWriterInterface;
use Spryker\Zed\Comment\Business\Writer\CommentThreadWriter;
use Spryker\Zed\Comment\Business\Writer\CommentThreadWriterInterface;
use Spryker\Zed\Comment\Business\Writer\CommentWriter;
use Spryker\Zed\Comment\Business\Writer\CommentWriterInterface;
use Spryker\Zed\Comment\CommentDependencyProvider;
use Spryker\Zed\Comment\Dependency\Facade\CommentToCustomerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface getRepository()
 * @method \Spryker\Zed\Comment\CommentConfig getConfig()
 */
class CommentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Comment\Business\Writer\CommentWriterInterface
     */
    public function createCommentWriter(): CommentWriterInterface
    {
        return new CommentWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createCommentThreadReader(),
            $this->createCommentThreadWriter(),
            $this->createCommentValidator(),
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Writer\CommentThreadWriterInterface
     */
    public function createCommentThreadWriter(): CommentThreadWriterInterface
    {
        return new CommentThreadWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createCommentTagWriter(),
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Writer\CommentTagWriterInterface
     */
    public function createCommentTagWriter(): CommentTagWriterInterface
    {
        return new CommentTagWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createCommentThreadReader(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Reader\CommentThreadReaderInterface
     */
    public function createCommentThreadReader(): CommentThreadReaderInterface
    {
        return new CommentThreadReader(
            $this->getRepository(),
            $this->getCommentExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Validator\CommentValidatorInterface
     */
    public function createCommentValidator(): CommentValidatorInterface
    {
        return new CommentValidator(
            $this->getCommentValidatorPlugins(),
            $this->getCommentAuthorValidatorStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Validator\CustomerCommentValidatorInterface
     */
    public function createCustomerCommentValidator(): CustomerCommentValidatorInterface
    {
        return new CustomerCommentValidator(
            $this->getCustomerFacade(),
            $this->getRepository(),
        );
    }

    /**
     * @return array<\Spryker\Zed\CommentExtension\Dependency\Plugin\CommentValidatorPluginInterface>
     */
    public function getCommentValidatorPlugins(): array
    {
        return $this->getProvidedDependency(CommentDependencyProvider::PLUGINS_COMMENT_VALIDATOR);
    }

    /**
     * @return list<\Spryker\Zed\CommentExtension\Dependency\Plugin\CommentAuthorValidatorStrategyPluginInterface>
     */
    public function getCommentAuthorValidatorStrategyPlugins(): array
    {
        return $this->getProvidedDependency(CommentDependencyProvider::PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY);
    }

    /**
     * @return list<\Spryker\Zed\CommentExtension\Dependency\Plugin\CommentExpanderPluginInterface>
     */
    public function getCommentExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CommentDependencyProvider::PLUGINS_COMMENT_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\Comment\Dependency\Facade\CommentToCustomerFacadeInterface
     */
    public function getCustomerFacade(): CommentToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(CommentDependencyProvider::FACADE_CUSTOMER);
    }
}
