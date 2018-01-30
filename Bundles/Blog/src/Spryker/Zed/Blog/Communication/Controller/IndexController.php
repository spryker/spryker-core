<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 *  @method \Spryker\Zed\Blog\Persistence\BlogRepositoryInterface getRepository()
 */
class IndexController extends AbstractController
{
    public function indexAction()
    {
        $br = $this->getRepository()->findBlogByName('name');

    }
}
