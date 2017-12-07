<?php

namespace Spryker\Client\NavigationStorage;

interface NavigationStorageClientInterface
{
    /**
     * Specification:
     * - Finds navigation tree in the Key-Value Storage.
     * - Returns the navigation tree with all the stored data if found, NULL otherwise.
     *
     * @api
     *
     * @param string $navigationKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\NavigationStorageTransfer|null
     */
    public function findNavigationTreeByKey($navigationKey, $localeName);
}
