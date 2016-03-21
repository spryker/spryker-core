<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Business\Internal;

use Spryker\Zed\Installer\Business\Model\AbstractInstaller;

class Install extends AbstractInstaller
{

    /**
     * @return void
     */
    public function install()
    {
        $this->alert('Installing TEST DATA'); //TODO create test data fixture with this installer
    }

}
