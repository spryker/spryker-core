<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi\Dependency\Facade;

interface CategoriesBackendApiToLocaleFacadeInterface
{
    /**
     * @return array<string, \Generated\Shared\Transfer\LocaleTransfer>
     */
    public function getLocaleCollection(): array;
}
