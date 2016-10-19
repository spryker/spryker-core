<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Util\Business;

use Spryker\Shared\Util\Text\Slug;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Util\UtilConfig getConfig()
 * @method \Spryker\Zed\Util\Persistence\UtilQueryContainerInterface getQueryContainer()
 */
class UtilBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Shared\Util\Text\SlugInterface
     */
    public function createTextSlug()
    {
        return new Slug();
    }

}
