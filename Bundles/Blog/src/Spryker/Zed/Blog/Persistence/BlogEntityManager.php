<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Persistence;

use Generated\Shared\Transfer\BlogCommentTransfer;
use Generated\Shared\Transfer\BlogTransfer;
use Orm\Zed\Blog\Persistence\SpyBlog;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\Blog\Persistence\BlogPersistenceFactory getFactory()
 */
class BlogEntityManager implements BlogEntityManagerInterface
{
    use DatabaseTransactionHandlerTrait;

    public function save(TransferInterface $transfer)
    {
        //loop properties
        //map transfer object to entity graph
        // save main
    }

    public function saveBlog(BlogTransfer $blogTransfer)
    {
        return $this->handleDatabaseTransaction(function () use ($blogTransfer) {

            $this->getFactory()->createBlogPluginExecutor()->executeBlogPreSavePlugins($blogTransfer);

            $blogEntity = $this->getFactory()
                ->createBlogMapper()
                ->fromTransferToEntity($blogTransfer, new SpyBlog());

            $blogEntity->save();

            if (count($blogTransfer->getComments()) > 0) {
                foreach ($blogTransfer->getComments() as $commentTransfer) {
                    $commentTransfer->setFkBlog($blogEntity->getPrimaryKey());
                    $this->saveBlogComment($commentTransfer);
                }
            }

            $blogTransfer->setIdBlog($blogEntity->getPrimaryKey());

            $this->getFactory()->createBlogPluginExecutor()->executeBlogPostSavePlugins($blogTransfer);

            $this->save($blogTransfer);

            return $blogTransfer;
        });


    }

    /**
     * @param \Generated\Shared\Transfer\BlogCommentTransfer $blogCommentTransfer
     *
     * @return \Generated\Shared\Transfer\BlogCommentTransfer
     */
    public function saveBlogComment(BlogCommentTransfer $blogCommentTransfer)
    {
        return $blogCommentTransfer;
    }
}
