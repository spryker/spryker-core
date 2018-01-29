<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Business;

use Spryker\Zed\Blog\Business\Model\Blog;
use Spryker\Zed\Blog\Persistence\BlogRepository;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Blog\Persistence\BlogRepositoryInterface getRepository()
 * @method \Spryker\Zed\Blog\Persistence\BlogEntityManagerInterface getEntityManager()
 */
class BlogBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Blog\Business\Model\Blog
     */
    public function createBlog()
    {
        return new Blog($this->getRepository(), $this->getEntityManager());
    }
}
