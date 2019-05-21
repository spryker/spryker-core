<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Business\Installer;

interface OauthCustomerScopeInstallerInterface
{
    /**
     * @return void
     */
    public function install(): void;
}
