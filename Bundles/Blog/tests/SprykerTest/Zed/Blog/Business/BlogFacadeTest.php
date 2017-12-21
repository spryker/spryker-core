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
use Spryker\Zed\Blog\Business\BlogFacade;

class BlogFacadeTest extends Unit
{
    /**
     * @return void
     */
    public function testSaveBlogShouldReturnId()
    {
        $blogTransfer = $this->createBlog();

        $this->assertNotEmpty($blogTransfer);
    }

    /**
     * @return void
     */
    public function testFindBlogByIdShouldReturnBlogTransfer()
    {
        $blogTransfer = $this->createBlog();

        $blogTransfer = $this->createBlogFacade()->findBlogById($blogTransfer->getIdBlog());

        $this->assertInstanceOf(BlogTransfer::class, $blogTransfer);
        $this->assertCount(1, $blogTransfer->getComments());
    }

    /**
     * @return void
     */
    public function testFilterBlogPostsShouldReturnCollection()
    {
        for ($i =0; $i < 50; $i++) {
            $this->createBlog();
        }

        $blogCriteriaFilterTransfer = (new BlogCriteriaFilterTransfer())
            ->setName('Blog name')
            ->setOffset(0)
            ->setLimit(2);

        $blogCollection = $this->createBlogFacade()->filterBlogPosts($blogCriteriaFilterTransfer);

        $this->assertCount(2, $blogCollection);

        $blogCriteriaFilterTransfer = (new BlogCriteriaFilterTransfer())
            ->setName('Blog name')
            ->setOffset(0)
            ->setLimit(2);

        $blogCollection = $this->createBlogFacade()->filterBlogPosts($blogCriteriaFilterTransfer);

        $this->assertCount(2, $blogCollection);

    }

    /**
     * @return \Spryker\Zed\Blog\Business\BlogFacade
     */
    protected function createBlogFacade()
    {
        return new BlogFacade();
    }

    /**
     * @return \Generated\Shared\Transfer\BlogTransfer
     */
    protected function createBlog()
    {
        $blogFacade = $this->createBlogFacade();

        $blogTransfer = (new BlogTransfer())
            ->setName('Blog name')
            ->setText('Text');

        $commentTransfer = new BlogCommentTransfer();
        $commentTransfer->setAuthor("It's a me Mario");
        $commentTransfer->setMessage('1 UP');

        $blogTransfer->addComment($commentTransfer);

        return $blogFacade->save($blogTransfer);
    }
}
