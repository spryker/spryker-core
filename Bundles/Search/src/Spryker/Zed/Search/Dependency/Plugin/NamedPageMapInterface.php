<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Dependency\Plugin;

/**
 * @deprecated Will be removed without replacement.
 */
interface NamedPageMapInterface extends PageMapInterface
{
    /**
     * Specification:
     *  - This name will use for mapping specific type to a proper search plugin class
     *
     * @api
     *
     * @return string
     */
    public function getName();
}
