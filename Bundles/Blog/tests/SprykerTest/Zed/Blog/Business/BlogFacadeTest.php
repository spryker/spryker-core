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
use Generated\Shared\Transfer\CriteriaTransfer;
use Generated\Shared\Transfer\SpyBlogCommentEntityTransfer;
use Generated\Shared\Transfer\SpyBlogCustomerEntityTransfer;
use Generated\Shared\Transfer\SpyBlogEntityTransfer;
use Generated\Shared\Transfer\SpyCustomerEntityTransfer;
use Orm\Zed\Blog\Persistence\SpyBlogQuery;
use Propel\Runtime\Propel;
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
        $blogCollection = $blogRepository->findBlogCollectionByFirstName(static::BLOG_NAME);

        $blogEntityTransfer = $blogCollection[0];

        $this->assertInstanceOf(SpyBlogEntityTransfer::class, $blogEntityTransfer);
        $this->assertCount(2, $blogEntityTransfer->getSpyBlogComments());

        $blogCommentTransfer = $blogEntityTransfer->getSpyBlogComments()[0];
        $blogCustomerTransfer = $blogCommentTransfer->getSpyBlogCustomers()[0];

        $this->assertInstanceOf(SpyBlogCommentEntityTransfer::class, $blogCommentTransfer);
        $this->assertInstanceOf(SpyBlogCustomerEntityTransfer::class, $blogCustomerTransfer);

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
     * @return void
     */
    public function testDeleteCommentShouldDropCommentFromDatabase()
    {
        $blogEntityManager = $this->createBlogEntityManager();
        $blogEntityTransfer = $this->createBlog();

        $blogCommentTransfer = $blogEntityTransfer->getSpyBlogComments()[0];
        $customerTransfer = $blogCommentTransfer->getSpyBlogCustomers()[0];

        $blogEntityManager->deleteBlogCustomerById($customerTransfer->getIdBlogCustomer());
        $blogEntityManager->deleteCommentById($blogCommentTransfer->getIdBlogComment());

        $updatedComments = $this->createBlogRepository()
            ->findBlogByName(self::BLOG_NAME)
            ->getSpyBlogComments();

        $this->assertCount(1, $updatedComments);

    }

    /**
     * @return void
     */
    public function testSaveFromFacadeShouldPersist()
    {
       $blogFacade = $this->createBlogFacade();
       $blogEntityTransfer = (new SpyBlogEntityTransfer())
            ->setName(self::BLOG_NAME)
            ->setText('Text');

       $transfer = $blogFacade->save($blogEntityTransfer);

       $this->assertNotEmpty($transfer->getIdBlog());
    }

    /**
     * @return void
     */
    public function testFilterBlogPosts()
    {
        $blogRepository = $this->createBlogRepository();

        $blogCriteriaFilterTransfer = new BlogCriteriaFilterTransfer();

        $this->createBlog();
        $this->createBlog();
        $this->createBlog();

        $criteriaTransfer = (new CriteriaTransfer())->setOffset(0)->setLimit(2);
        $blogCriteriaFilterTransfer->setCriteria($criteriaTransfer);

        $blogCollection = $blogRepository->filterBlogPosts($blogCriteriaFilterTransfer);
        $this->assertCount(2, $blogCollection);

        $blogComments = $blogCollection[0]->getSpyBlogComments();
        $this->assertCount(2, $blogComments);

        $blogCustomers = $blogComments[0]->getSpyBlogCustomers();
        $this->assertCount(1, $blogCustomers);
    }

    /**
     * @return void
     */
    public function testUpdateBlogShouldUpdateExisting()
    {
        $entityManager = $this->createBlogEntityManager();

        $blogTransfer = $this->createBlog();

        $name = 'new';

        $blogTransfer->setName($name);

        //update
        $entityManager->save($blogTransfer);

        $blogTransfer = $this->createBlogRepository()->findBlogByName($name);

        $this->assertNotNull($blogTransfer);
        $this->assertSame($name, $blogTransfer->getName());
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

        $blogCustomerEntityTransfer = new SpyBlogCustomerEntityTransfer();
        $blogCustomerEntityTransfer->setName('test');

        $blogCommentEntityTransfer = new SpyBlogCommentEntityTransfer();
        $blogCommentEntityTransfer->setAuthor("It's a me a Mario!");
        $blogCommentEntityTransfer->setMessage('1 UP');
        $blogCommentEntityTransfer->addSpyBlogCustomers($blogCustomerEntityTransfer);

        $blogEntityTransfer->addSpyBlogComments($blogCommentEntityTransfer);

        $blogCommentEntityTransfer = new SpyBlogCommentEntityTransfer();
        $blogCommentEntityTransfer->setAuthor("It's a me a Mario!");
        $blogCommentEntityTransfer->setMessage('2 UP');
        $blogCommentEntityTransfer->addSpyBlogCustomers($blogCustomerEntityTransfer);

        $blogEntityTransfer->addSpyBlogComments($blogCommentEntityTransfer);

        $blogEntityTransfer = $blogEntityManager->saveBlog($blogEntityTransfer);

        return $blogEntityTransfer;
    }
}
