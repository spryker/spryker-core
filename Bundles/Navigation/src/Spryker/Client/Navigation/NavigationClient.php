<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Navigation;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Navigation\NavigationFactory getFactory()
 */
class NavigationClient extends AbstractClient implements NavigationClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $navigationKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\NavigationTreeTransfer|null
     */
    public function findNavigationTreeByKey($navigationKey, $localeName)
    {
        return $this->getFactory()
            ->createNavigationReader()
            ->findNavigationTreeByNavigationKey($navigationKey, $localeName);
    }
}
