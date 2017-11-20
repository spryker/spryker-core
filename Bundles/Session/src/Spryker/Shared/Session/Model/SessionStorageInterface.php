<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Model;

interface SessionStorageInterface
{
    /**
     * @return array
     */
    public function getOptions();

    /**
     * @return \SessionHandlerInterface
     */
    public function getAndRegisterHandler();
}
