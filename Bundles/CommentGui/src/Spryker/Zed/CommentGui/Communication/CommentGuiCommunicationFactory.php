<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentGui\Communication;

use Spryker\Zed\CommentGui\CommentGuiDependencyProvider;
use Spryker\Zed\CommentGui\Dependency\Facade\CommentGuiToCommentFacadeInterface;
use Spryker\Zed\CommentGui\Dependency\Facade\CommentGuiToUserFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @method \Spryker\Zed\CommentGui\CommentGuiConfig getConfig()
 */
class CommentGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CommentGui\Dependency\Facade\CommentGuiToCommentFacadeInterface
     */
    public function getCommentFacade(): CommentGuiToCommentFacadeInterface
    {
        return $this->getProvidedDependency(CommentGuiDependencyProvider::FACADE_COMMENT);
    }

    /**
     * @return \Spryker\Zed\CommentGui\Dependency\Facade\CommentGuiToUserFacadeInterface
     */
    public function getUserFacade(): CommentGuiToUserFacadeInterface
    {
        return $this->getProvidedDependency(CommentGuiDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    public function getCsrfTokenManager(): CsrfTokenManagerInterface
    {
        return $this->getProvidedDependency(CommentGuiDependencyProvider::SERVICE_FORM_CSRF_PROVIDER);
    }
}
