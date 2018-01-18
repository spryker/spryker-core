<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Persistence;

use Generated\Shared\Transfer\BlogCommentTransfer;
use Generated\Shared\Transfer\BlogCriteriaFilterTransfer;
use Generated\Shared\Transfer\BlogTransfer;
use Orm\Zed\Blog\Persistence\Map\SpyBlogTableMap;
use Orm\Zed\Blog\Persistence\SpyBlog;
use Orm\Zed\Blog\Persistence\SpyBlogComment;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
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

        //feature specific
        if ($blogCriteriaFilterTransfer->getName()) {
            $blogQuery->filterByName($blogCriteriaFilterTransfer->getName(), Criteria::LIKE);
        }

        if ($blogCriteriaFilterTransfer->getText()) {
            $blogQuery->filterByText($blogCriteriaFilterTransfer->getText(), Criteria::LIKE);
        }




        //group to generic transfer
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
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\SpyCustomerEntityTransfer
     */
    public function findCustomerById($idCustomer)
    {
        $spyCustomerEntityTransfer = SpyCustomerQuery::create()
            ->filterByIdCustomer($idCustomer)
            ->setFormatter(
                //propel entity to spy entity transfer formatter
            )
            ->findOne();


        return $spyCustomerEntityTransfer;
    }

    /**
     * @param string $firstName
     *
     * Criteria
     *  - limit  = int
     *  - offset = int
     *  - sortBy = string
     *
     * @return \Generated\Shared\Transfer\SpyCustomerEntityTransfer[]
     */
    public function findCustomersByFirstName($firstName, $criteria)
    {
        $spyCustomerQuery = $this->queryCustomerByFirstName($firstName);

        return $this->buildQueryFromCriteria($spyCustomerQuery, $criteria)->find();
    }

    /**
     * @param string $firstName
     *
     * @return \Generated\Shared\Transfer\SpyCustomerEntityTransfer
     */
    public function findCustomerByFirstName($firstName)
    {
        $spyCustomerQuery = $this->queryCustomerByFirstName($firstName);

        return $spyCustomerQuery->findOne();
    }
    /**
     * @param string $firstName
     *
     * @return int
     */
    public function countCustomersByFirstName($firstName, $criteria)
    {
        $spyCustomerQuery = $this->queryCustomerByFirstName($firstName);

        return $this->buildQueryFromCriteria($spyCustomerQuery, $criteria)->count();
    }

    /**
     * @param \Generated\Shared\Transfer\BlogTransfer $blogTransfer
     *
     * @return \Generated\Shared\Transfer\BlogTransfer
     */
    public function saveBlog(BlogTransfer $blogTransfer)
    {
        $blogTransfer->requireName()->requireText();

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
        $blogCommentTransfer->requireMessage()->requireAuthor();

        $blogCommentEntity = $this->getFactory()
            ->createCommentMapper()
            ->fromTransferToEntity($blogCommentTransfer, new SpyBlogComment());

        $blogCommentEntity->save();

        $blogCommentTransfer->setIdComment($blogCommentEntity->getPrimaryKey());

        return $blogCommentTransfer;
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function removeBlogById($id)
    {
        $this->handleDatabaseTransaction(function () use ($id) {
            $this->getFactory()->createBlogCommentQuery()
                ->filterByFkBlog($id)
                ->delete();

            $this->getFactory()->createBlogQuery()
                ->filterByIdBlog($id)
                ->delete();
        });
    }

    /**
     * @param $firstName
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function queryCustomerByFirstName($firstName)
    {
        $spyCustomerQuery = SpyCustomerQuery::create()
            ->setFormatter(
            //propel entity to spy entity transfer formatter
            )
            ->filterByFirstName($firstName);

        return $spyCustomerQuery;
    }
}
