<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedUi;

use Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface;

interface ZedUiFactoryInterface
{
    /**
     * Specification:
     * - Creates form response builder.
     *
     * @api
     *
     * @return \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface
     */
    public function createZedUiFormResponseBuilder(): ZedUiFormResponseBuilderInterface;
}
