<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentNavigation\Mapper;

use Generated\Shared\Transfer\ContentNavigationTypeTransfer;

interface ContentNavigationTypeMapperInterface
{
    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentNavigationTypeTransfer|null
     */
    public function executeNavigationTypeByKey(string $contentKey, string $localeName): ?ContentNavigationTypeTransfer;
}
