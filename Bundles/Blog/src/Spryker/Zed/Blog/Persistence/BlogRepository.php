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
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Blog\Persistence\BlogPersistenceFactory getFactory()
 */
class BlogRepository extends AbstractRepository implements BlogRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyBlogEntityTransfer[]
     */
    public function filterBlogPosts(BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer)
    {
        $blogQuery = $this->getFactory()
            ->createBlogQuery();

        if ($blogCriteriaFilterTransfer->getName()) {
            $blogQuery->filterByName($blogCriteriaFilterTransfer->getName(), Criteria::LIKE);
        }

        if ($blogCriteriaFilterTransfer->getText()) {
            $blogQuery->filterByText($blogCriteriaFilterTransfer->getText(), Criteria::LIKE);
        }

        $collection = $this->buildQueryFromCriteria($blogQuery, $blogCriteriaFilterTransfer->getCriteria())
            ->find();

        $comments = $this->populateCollectionWithRelation($collection, 'SpyBlogComment');
        $this->populateCollectionWithRelation($comments, 'SpyBlogCustomer');

        return $collection;
    }

    /**
     * @api
     *
     * @dependency Customer, Product, Store should be included in composer.json
     *
     * @param string $firstName
     * @param \Generated\Shared\Transfer\CriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SpyBlogEntityTransfer[]
     */
    public function findBlogCollectionByFirstName($firstName, CriteriaTransfer $criteriaTransfer = null)
    {
        $customerQuery = $this->queryBlogByName($firstName)
            ->joinWithSpyBlogComment()
            ->useSpyBlogCommentQuery()
               ->joinWithSpyBlogCustomer()
            ->endUse();

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
