<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FooBar\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\FooBar\Business\FooBarBusinessFactory getFactory()
 * @method \Spryker\Zed\FooBar\Persistence\FooBarRepositoryInterface getRepository()
 * @method \Spryker\Zed\FooBar\Persistence\FooBarEntityManagerInterface getEntityManager()
 */
class FooBarFacade extends AbstractFacade implements FooBarFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $foo
     *
     * @return bool
     */
    public function addSomethingNew(string $foo): bool
    {
    }
}
