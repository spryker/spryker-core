<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Communication;

interface BundleControllerActionInterface
{
    /**
     * @return string
     */
    public function getBundle();

    /**
     * @return string
     */
    public function getController();

    /**
     * @return string
     */
    public function getAction();
}
