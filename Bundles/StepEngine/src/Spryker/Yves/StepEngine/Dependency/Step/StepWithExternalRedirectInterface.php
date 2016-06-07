<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Dependency\Step;

interface StepWithExternalRedirectInterface extends StepInterface
{

    /**
     * Return external redirect url, when redirect occurs not within same application. Used after execute.
     *
     * @return string
     */
    public function getExternalRedirectUrl();

}
