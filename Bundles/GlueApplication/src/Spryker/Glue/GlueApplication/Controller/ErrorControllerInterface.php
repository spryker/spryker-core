<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Controller;

interface ErrorControllerInterface
{
    /**
     * @return mixed
     */
    public function badRequestAction();

    /**
     * @return mixed
     */
    public function resourceNotFoundAction();
}
