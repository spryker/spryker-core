<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Business\Model;

use Generated\Shared\Transfer\BlogCriteriaFilterTransfer;
use Generated\Shared\Transfer\BlogTransfer;
use Generated\Shared\Transfer\SpyBlogEntityTransfer;
use Spryker\Zed\Blog\Persistence\BlogEntityManagerInterface;
use Spryker\Zed\Blog\Persistence\BlogRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class Blog
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Blog\Persistence\BlogRepositoryInterface
     */
    protected $blogRepository;

    /**
     * @var \Spryker\Zed\Blog\Persistence\BlogEntityManagerInterface
     */
    protected $blogEntityManager;

    /**
     * @param \Spryker\Zed\Blog\Persistence\BlogRepositoryInterface $blogRepository
     * @param \Spryker\Zed\Blog\Persistence\BlogEntityManagerInterface $blogEntityManager
     */
    public function __construct(
        BlogRepositoryInterface $blogRepository,
        BlogEntityManagerInterface $blogEntityManager
    ) {
        $this->blogRepository = $blogRepository;
        $this->blogEntityManager = $blogEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyBlogEntityTransfer $blogTransfer
     *
     * @return \Generated\Shared\Transfer\SpyBlogEntityTransfer
     */
    public function save(SpyBlogEntityTransfer $blogTransfer)
    {
        return $this->getTransactionHandler()->handleTransaction(function() use($blogTransfer) {

            //Everything in this blog will be done in a single transaction.

            return $this->executeSaveBlogTransaction($blogTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\SpyBlogEntityTransfer $blogTransfer
     *
     * @return \Generated\Shared\Transfer\SpyBlogEntityTransfer
     */
    protected function executeSaveBlogTransaction(SpyBlogEntityTransfer $blogTransfer)
    {
        return $this->blogEntityManager->saveBlog($blogTransfer);
    }

    /**
     * @param int $name
     *
     * @return \Generated\Shared\Transfer\SpyBlogEntityTransfer
     */
    public function findBlogByName($name)
    {
        return $this->blogRepository->findBlogByName($name);
    }

    /**
     * @param \Generated\Shared\Transfer\BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyBlogEntityTransfer[]
     */
    public function filterBlogPosts(BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer)
    {
        return $this->blogRepository->filterBlogPosts($blogCriteriaFilterTransfer);
    }
}
