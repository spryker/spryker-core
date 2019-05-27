<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Comment\Business\Reader\CommentReader;
use Spryker\Zed\Comment\Business\Reader\CommentReaderInterface;
use Spryker\Zed\Comment\Business\ReferenceGenerator\CommentReferenceGenerator;
use Spryker\Zed\Comment\Business\ReferenceGenerator\CommentReferenceGeneratorInterface;
use Spryker\Zed\Comment\Business\Sanitizer\CommentVersionSanitizer;
use Spryker\Zed\Comment\Business\Sanitizer\CommentVersionSanitizerInterface;
use Spryker\Zed\Comment\Business\Sender\CommentSender;
use Spryker\Zed\Comment\Business\Sender\CommentSenderInterface;
use Spryker\Zed\Comment\Business\Sender\CommentUserSender;
use Spryker\Zed\Comment\Business\Sender\CommentUserSenderInterface;
use Spryker\Zed\Comment\Business\Status\CommentStatus;
use Spryker\Zed\Comment\Business\Status\CommentStatusInterface;
use Spryker\Zed\Comment\Business\Status\CommentUserStatus;
use Spryker\Zed\Comment\Business\Status\CommentUserStatusInterface;
use Spryker\Zed\Comment\Business\Validator\CommentTimeValidator;
use Spryker\Zed\Comment\Business\Validator\CommentTimeValidatorInterface;
use Spryker\Zed\Comment\Business\Writer\CommentTerminator;
use Spryker\Zed\Comment\Business\Writer\CommentTerminatorInterface;
use Spryker\Zed\Comment\Business\Writer\CommentUserTerminator;
use Spryker\Zed\Comment\Business\Writer\CommentUserTerminatorInterface;
use Spryker\Zed\Comment\Business\Writer\CommentUserWriter;
use Spryker\Zed\Comment\Business\Writer\CommentUserWriterInterface;
use Spryker\Zed\Comment\Business\Writer\CommentWriter;
use Spryker\Zed\Comment\Business\Writer\CommentWriterInterface;
use Spryker\Zed\Comment\Dependency\Facade\CommentToCalculationFacadeInterface;
use Spryker\Zed\Comment\Dependency\Facade\CommentToCartFacadeInterface;
use Spryker\Zed\Comment\Dependency\Facade\CommentToCompanyUserFacadeInterface;
use Spryker\Zed\Comment\CommentDependencyProvider;

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
            $this->getConfig(),
            $this->getEntityManager(),
            $this->createCommentReader(),
            $this->createCommentReferenceGenerator(),
            $this->createCommentVersionSanitizer(),
            $this->createCommentStatus()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Writer\CommentUserWriterInterface
     */
    public function createCommentUserWriter(): CommentUserWriterInterface
    {
        return new CommentUserWriter(
            $this->getConfig(),
            $this->getEntityManager(),
            $this->createCommentReader(),
            $this->createCommentReferenceGenerator(),
            $this->createCommentVersionSanitizer(),
            $this->createCommentUserStatus()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Validator\CommentTimeValidatorInterface
     */
    public function createCommentTimeValidator(): CommentTimeValidatorInterface
    {
        return new CommentTimeValidator(
            $this->createCommentReader()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Sender\CommentSenderInterface
     */
    public function createCommentSender(): CommentSenderInterface
    {
        return new CommentSender(
            $this->getEntityManager(),
            $this->createCommentReader()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Sender\CommentUserSenderInterface
     */
    public function createCommentUserSender(): CommentUserSenderInterface
    {
        return new CommentUserSender(
            $this->getEntityManager(),
            $this->createCommentReader()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Writer\CommentTerminatorInterface
     */
    public function createCommentTerminator(): CommentTerminatorInterface
    {
        return new CommentTerminator(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createCommentReader(),
            $this->createCommentStatus()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Writer\CommentUserTerminatorInterface
     */
    public function createCommentUserTerminator(): CommentUserTerminatorInterface
    {
        return new CommentUserTerminator(
            $this->getEntityManager(),
            $this->createCommentReader(),
            $this->createCommentUserStatus()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Reader\CommentReaderInterface
     */
    public function createCommentReader(): CommentReaderInterface
    {
        return new CommentReader(
            $this->getRepository(),
            $this->getCompanyUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Status\CommentStatusInterface
     */
    public function createCommentStatus(): CommentStatusInterface
    {
        return new CommentStatus(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Status\CommentUserStatusInterface
     */
    public function createCommentUserStatus(): CommentUserStatusInterface
    {
        return new CommentUserStatus(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\ReferenceGenerator\CommentReferenceGeneratorInterface
     */
    public function createCommentReferenceGenerator(): CommentReferenceGeneratorInterface
    {
        return new CommentReferenceGenerator(
            $this->getConfig(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\Sanitizer\CommentVersionSanitizerInterface
     */
    public function createCommentVersionSanitizer(): CommentVersionSanitizerInterface
    {
        return new CommentVersionSanitizer(
            $this->getCartFacade(),
            $this->getCalculationFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Dependency\Facade\CommentToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): CommentToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(CommentDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\Comment\Dependency\Facade\CommentToCalculationFacadeInterface
     */
    public function getCalculationFacade(): CommentToCalculationFacadeInterface
    {
        return $this->getProvidedDependency(CommentDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @return \Spryker\Zed\Comment\Dependency\Facade\CommentToCartFacadeInterface
     */
    public function getCartFacade(): CommentToCartFacadeInterface
    {
        return $this->getProvidedDependency(CommentDependencyProvider::FACADE_CART);
    }
}
