<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Blog\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\BlogCommentTransfer;
use Generated\Shared\Transfer\BlogCriteriaFilterTransfer;
use Generated\Shared\Transfer\BlogTransfer;
use Generated\Shared\Transfer\SpyBlogCommentEntityTransfer;
use Generated\Shared\Transfer\SpyBlogEntityTransfer;
use Generated\Shared\Transfer\SpyCustomerEntityTransfer;
use Orm\Zed\Blog\Persistence\SpyBlogQuery;
use Spryker\Zed\Blog\Business\BlogFacade;
use Spryker\Zed\Blog\Persistence\BlogEntityManager;
use Spryker\Zed\Blog\Persistence\BlogRepository;

class BlogFacadeTest extends Unit
{
    const BLOG_NAME = 'Blog name';

    /**
     * @return void
     */
    public function testFindBlogListByFirstName()
    {
        $blogRepository = $this->createBlogRepository();
        $this->createBlog();
        $blogCollection = $blogRepository->findBlogListByFirstName(static::BLOG_NAME);

        $blogEntityTransfer = $blogCollection[0];

        $this->assertInstanceOf(SpyBlogEntityTransfer::class, $blogEntityTransfer);
        $this->assertCount(2, $blogEntityTransfer->getSpyBlogComments());
        $this->assertInstanceOf(SpyBlogCommentEntityTransfer::class, $blogEntityTransfer->getSpyBlogComments()[0]);

    }

    /**
     * @return void
     */
    public function testFindBlogByFirstName()
    {
        $blogRepository = $this->createBlogRepository();
        $this->createBlog();
        $spyCustomerEntityTransfer = $blogRepository->findBlogByName(static::BLOG_NAME);

        $this->assertInstanceOf(SpyBlogEntityTransfer::class, $spyCustomerEntityTransfer);
    }

    /**
     * @return void
     */
    public function testCountBlogByFirstName()
    {
        $blogRepository = $this->createBlogRepository();
        $this->createBlog();
        $count = $blogRepository->countBlogByName(static::BLOG_NAME);

        $this->assertEquals(1, $count);
    }

    /**
     * @return \Spryker\Zed\Blog\Persistence\BlogRepository
     */
    protected function createBlogRepository()
    {
        return new BlogRepository();
    }

    /**
     * @return \Spryker\Zed\Blog\Persistence\BlogEntityManager
     */
    protected function createBlogEntityManager()
    {
        return new BlogEntityManager();
    }

    /**
     * @return \Spryker\Zed\Blog\Business\BlogFacade
     */
    protected function createBlogFacade()
    {
        return new BlogFacade();
    }

    /**
     * @return \Generated\Shared\Transfer\SpyBlogEntityTransfer
     */
    protected function createBlog()
    {
        $blogEntityManager = $this->createBlogEntityManager();

        $blogEntityTransfer = (new SpyBlogEntityTransfer())
            ->setName(self::BLOG_NAME)
            ->setText('Text');

        //not working
        $blogCommentEntityTransfer = new SpyBlogCommentEntityTransfer();
        $blogCommentEntityTransfer->setAuthor("It's a me a Mario!");
        $blogCommentEntityTransfer->setMessage('1 UP');

        $blogEntityTransfer->addSpyBlogComments($blogCommentEntityTransfer);

        $blogCommentEntityTransfer = new SpyBlogCommentEntityTransfer();
        $blogCommentEntityTransfer->setAuthor("It's a me a Mario!");
        $blogCommentEntityTransfer->setMessage('2 UP');

        $blogEntityTransfer->addSpyBlogComments($blogCommentEntityTransfer);

        $blogEntityTransfer = $blogEntityManager->saveBlog($blogEntityTransfer);

        return $blogEntityTransfer;
    }
}
