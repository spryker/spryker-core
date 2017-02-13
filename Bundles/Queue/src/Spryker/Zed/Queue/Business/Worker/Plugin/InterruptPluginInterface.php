<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Worker\Plugin;

interface InterruptPluginInterface
{

    /**
     * @return void
     */
    public function tick();

    /**
     * @return bool
     */
    public function isInterrupted();
}
