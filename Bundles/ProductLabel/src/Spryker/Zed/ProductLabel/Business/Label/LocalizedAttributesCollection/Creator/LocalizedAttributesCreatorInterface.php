<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\Creator;

interface LocalizedAttributesCreatorInterface
{
    /**
     * @return void
     */
    public function createMissingLocalizedAttributes(): void;
}
