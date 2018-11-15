<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Dependency\Step;

interface StepWithPostConditionErrorRouteInterface extends StepInterface
{
    /**
     * @return string|null
     */
    public function getPostConditionErrorRoute();
}
