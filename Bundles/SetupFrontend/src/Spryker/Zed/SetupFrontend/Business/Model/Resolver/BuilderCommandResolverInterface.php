<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Resolver;

interface BuilderCommandResolverInterface
{
    /**
     * @return string
     */
    public function getYvesBuildCommand(): string;
}
