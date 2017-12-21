<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Persistence;

use Generated\Shared\Transfer\BlogCommentTransfer;
use Generated\Shared\Transfer\BlogCriteriaFilterTransfer;
use Generated\Shared\Transfer\BlogTransfer;
use Orm\Zed\Blog\Persistence\SpyBlog;
use Orm\Zed\Blog\Persistence\SpyBlogComment;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Blog\Persistence\BlogPersistenceFactory getFactory()
 */
class BlogRepository extends AbstractRepository implements BlogRepositoryInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param \Generated\Shared\Transfer\BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\BlogCriteriaFilterTransfer[]
     */
    public function filterBlogPosts(BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer)
    {
        $blogQuery = $this->getFactory()
            ->createBlogQuery()
            ->joinSpyBlogComment();

        if ($blogCriteriaFilterTransfer->getName()) {
            $blogQuery->filterByName($blogCriteriaFilterTransfer->getName(), Criteria::LIKE);
        }

        if ($blogCriteriaFilterTransfer->getText()) {
            $blogQuery->filterByText($blogCriteriaFilterTransfer->getText(), Criteria::LIKE);
        }

        if ($blogCriteriaFilterTransfer->getOffset()) {
            $blogQuery->offset($blogCriteriaFilterTransfer->getOffset());
        }

        if ($blogCriteriaFilterTransfer->getLimit()) {
            $blogQuery->limit($blogCriteriaFilterTransfer->getLimit());
        }

        if ($blogCriteriaFilterTransfer->getSortBy()) {
            $blogQuery->orderBy($blogCriteriaFilterTransfer->getSortBy());
        }

        $results = $blogQuery->find();

        $blogCollection = [];
        foreach ($results as $blogEntity) {

            $blogTransfer = $this->getFactory()
                ->createBlogMapper()
                ->fromEntityToTransfer($blogEntity, new BlogTransfer());

            foreach ($blogEntity->getSpyBlogComments() as $commentEntity) {
                $commentTransfer = $this->getFactory()
                    ->createCommentMapper()
                    ->fromEntityToTransfer($commentEntity, new BlogCommentTransfer());

                $blogTransfer->addComment($commentTransfer);
            }

            $blogCollection[] = $blogTransfer;
        }

        return $blogCollection;
    }

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\BlogTransfer|null
     */
    public function findBlogById($id)
    {
        $blogEntity = $this->getFactory()
            ->createBlogQuery()
            ->filterByIdBlog($id)
            ->findOne();

        if (!$blogEntity) {
            return null;
        }

        $blogTransfer = $this->getFactory()
            ->createBlogMapper()
            ->fromEntityToTransfer($blogEntity, new BlogTransfer());

        foreach ($blogEntity->getSpyBlogComments() as $commentEntity) {
            $commentTransfer = $this->getFactory()
                ->createCommentMapper()
                ->fromEntityToTransfer($commentEntity, new BlogCommentTransfer());

            $blogTransfer->addComment($commentTransfer);
        }

        return $blogTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\BlogTransfer $blogTransfer
     *
     * @return \Generated\Shared\Transfer\BlogTransfer
     */
    public function persistBlog(BlogTransfer $blogTransfer)
    {
        $blogTransfer->requireName()->requireText();

        return $this->handleDatabaseTransaction(function () use ($blogTransfer) {

            $blogEntity = $this->getFactory()
                ->createBlogMapper()
                ->fromTransferToEntity($blogTransfer, new SpyBlog());

            $blogEntity->save();

            if (count($blogTransfer->getComments()) > 0) {
                foreach ($blogTransfer->getComments() as $commentTransfer) {
                    $commentTransfer->setFkBlog($blogEntity->getPrimaryKey());
                    $this->persistBlogComment($commentTransfer);
                }
            }

            $blogTransfer->setIdBlog($blogEntity->getPrimaryKey());

            return $blogTransfer;
        });
    }

    /**
     * @param \Generated\Shared\Transfer\BlogCommentTransfer $blogCommentTransfer
     *
     * @return \Generated\Shared\Transfer\BlogCommentTransfer
     */
    public function persistBlogComment(BlogCommentTransfer $blogCommentTransfer)
    {
        $blogCommentTransfer->requireMessage()->requireAuthor();

        $blogCommentEntity = $this->getFactory()
            ->createCommentMapper()
            ->fromTransferToEntity($blogCommentTransfer, new SpyBlogComment());

        $blogCommentEntity->save();

        $blogCommentTransfer->setIdComment($blogCommentEntity->getPrimaryKey());

        return $blogCommentTransfer;
    }
}
