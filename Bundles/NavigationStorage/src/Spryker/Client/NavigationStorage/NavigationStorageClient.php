<?php

namespace Spryker\Client\NavigationStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\NavigationStorage\NavigationStorageFactory getFactory()
 */
class NavigationStorageClient extends AbstractClient implements NavigationStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $navigationKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\NavigationStorageTransfer|null
     */
    public function findNavigationTreeByKey($navigationKey, $localeName)
    {
        return $this->getFactory()
            ->createNavigationStorage()
            ->findNavigationTreeByNavigationKey($navigationKey, $localeName);
    }
}
