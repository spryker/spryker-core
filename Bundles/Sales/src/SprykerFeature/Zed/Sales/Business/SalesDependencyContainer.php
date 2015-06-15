<?php

namespace SprykerFeature\Zed\Sales\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;

/**
 * @method SalesBusiness getFactory()
 */
class SalesDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return CommentManager
     */
    public function createCommentsManager()
    {
        return $this->getFactory()->createModelCommentManager(
            $this->createCommentsQueryContainer()
        );
    }

    /**
     * @return SalesQueryContainerInterface
     */
    public function createCommentsQueryContainer()
    {
        return $this->getLocator()->sales()->queryContainer();
    }
}
