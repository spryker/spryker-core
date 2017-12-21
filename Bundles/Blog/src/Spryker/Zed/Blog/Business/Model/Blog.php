<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Business\Model;

use Generated\Shared\Transfer\BlogCriteriaFilterTransfer;
use Generated\Shared\Transfer\BlogTransfer;
use Spryker\Zed\Blog\Persistence\BlogRepositoryInterface;

class Blog
{
    /**
     * @var \Spryker\Zed\Blog\Persistence\BlogRepositoryInterface
     */
    protected $blogRepository;

    /**
     * @param \Spryker\Zed\Blog\Persistence\BlogRepositoryInterface $blogRepository
     */
    public function __construct(BlogRepositoryInterface $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\BlogTransfer $blogTransfer
     *
     * @return \Generated\Shared\Transfer\BlogTransfer
     */
    public function save(BlogTransfer $blogTransfer)
    {
        return $this->blogRepository->saveBlog($blogTransfer);

    }

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\BlogTransfer
     */
    public function findBlogById($id)
    {
        return $this->blogRepository->findBlogById($id);
    }

    /**
     * @param \Generated\Shared\Transfer\BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\BlogCriteriaFilterTransfer[]
     */
    public function filterBlogPosts(BlogCriteriaFilterTransfer $blogCriteriaFilterTransfer)
    {
        return $this->blogRepository->filterBlogPosts($blogCriteriaFilterTransfer);
    }


    /**
     * @param $id
     */
    public function removeBlogById($id)
    {
        $this->blogRepository->removeBlogById($id);
    }

}
