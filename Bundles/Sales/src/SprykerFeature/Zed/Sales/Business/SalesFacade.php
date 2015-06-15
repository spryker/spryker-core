<?php

namespace SprykerFeature\Zed\Sales\Business;

use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class SalesFacade extends AbstractFacade
{
    /**
     * @throws \Exception
     */
    public function saveComment(CommentTransfer $commentTransfer)
    {
//        return $this->factory
//            ->createModelCommentManager(Locator::getInstance(), $this->factory)
//            ->saveComment($commentTransfer)
//        ;
        $commentsManager = $this->getDependencyContainer()->createCommentsManager();
        $commentsManager->saveComment($commentTransfer);

        return $commentsManager->convertToTransfer();
    }

    /**
     * @param Order $transferOrder
     * @param RequestInterface $request
     * @return ModelResult
     */
    public function saveOrder(OrderTransfer $transferOrder, RequestInterface $request)
    {
//        $salesManager = $this->getDependencyContainer()->get

        return $this->factory
            ->createModelOrderManager(Locator::getInstance(), $this->factory)
            ->saveOrder($transferOrder, $request);
    }
}
