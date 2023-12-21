<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Communication\Builder;

interface SecurityGuiOptionsBuilderInterface
{
    /**
     * @return array<mixed>
     */
    public function buildOptions(): array;
}
