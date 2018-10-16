<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\UserLocale\Business\UserLocaleBusinessFactory getFactory()
 */
class UserLocaleFacade extends AbstractFacade implements UserLocaleFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function install(): void
    {
        $this->getFactory()->createInstaller()->install();
    }
}
