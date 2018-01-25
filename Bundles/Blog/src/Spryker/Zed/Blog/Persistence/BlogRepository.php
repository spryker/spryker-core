<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Persistence;

use Generated\Shared\Transfer\BlogCommentTransfer;
use Generated\Shared\Transfer\BlogCriteriaFilterTransfer;
use Generated\Shared\Transfer\BlogTransfer;
use Generated\Shared\Transfer\CriteriaTransfer;
use Orm\Zed\Blog\Persistence\Map\SpyBlogTableMap;
use Orm\Zed\Blog\Persistence\SpyBlog;
use Orm\Zed\Blog\Persistence\SpyBlogComment;
use Orm\Zed\Blog\Persistence\SpyBlogQuery;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Propel\Runtime\Formatter\ArrayFormatter;
use Spryker\Zed\Kernel\Persistence\Repository\TransferObjectFormatter;
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

        $results = $blogQuery
            ->setFormatter(ArrayFormatter::class)
            ->find();

        $blogCollection = [];
        foreach ($results as $blogArray) {

            $blogTransfer = $this->getFactory()
                ->createBlogMapper()
                ->toTransfer($blogArray, new BlogTransfer());

            foreach ($blogArray['spy_blog_comments'] as $commentArray) {
                $commentTransfer = $this->getFactory()
                    ->createCommentMapper()
                    ->toTransfer($commentArray, new BlogCommentTransfer());

                $blogTransfer->addComment($commentTransfer);
            }

            $blogCollection[] = $blogTransfer;
        }

        return $blogCollection;
    }

    /**
     * @api
     *
     * @dependency Customer, Product, Store  should be included in composer.json
     *
     * @param string $firstName
     *
     * Criteria
     *  - limit  = int
     *  - offset = int
     *  - sortBy = string
     *
     * @param \Generated\Shared\Transfer\CriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SpyBlogEntityTransfer[]
     */
    public function findBlogListByFirstName($firstName, CriteriaTransfer $criteriaTransfer = null)
    {
        $customerQuery = $this->queryBlogByName($firstName)
            ->joinWithSpyBlogComment();

        return $this->buildQueryFromCriteria($customerQuery, $criteriaTransfer)->find();
    }

    /**
     * @param string $firstName
     *
     * @return \Generated\Shared\Transfer\SpyBlogEntityTransfer
     */
    public function findBlogByName($firstName)
    {
        $customerQuery = $this->queryBlogByName($firstName)
            ->joinWithSpyBlogComment();

        return $this->buildQueryFromCriteria($customerQuery)->find()[0];
    }

    /**
     * @param string $firstName
     *
     * @return int
     */
    public function countBlogByName($firstName)
    {
        $customerQuery = $this->queryBlogByName($firstName);

        return $this->buildQueryFromCriteria($customerQuery)->count();
    }

    /**
     * @param string $name
     *
     * @return \Orm\Zed\Blog\Persistence\SpyBlogQuery
     */
    protected function queryBlogByName($name)
    {
        return $this->getFactory()
            ->createBlogQuery()
            ->filterByName($name);
    }
}
