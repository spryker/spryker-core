<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\NavigationStorage\Storage;

interface NavigationStorageInterface
{

    /**
     * @param string $navigationKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\NavigationStorageTransfer|null
     */
    public function findNavigationTreeByNavigationKey($navigationKey, $localeName);
}
