<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FooBar\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FooBar\FooBarConfig getConfig()
 * @method \Spryker\Zed\FooBar\Business\FooBarBusinessFactory getFactory()
 * @method \Spryker\Zed\FooBar\Persistence\FooBarEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\FooBar\Persistence\FooBarQueryContainerInterface getQueryContainer()
 */
class FooBarBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return bool
     */
    public function createFooBar(): bool
    {
        return new Spryker\Zed\FooBar\Business\Model\FooBar();
    }
}
