<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentNavigation;

use Generated\Shared\Transfer\ContentNavigationTypeTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ContentNavigation\ContentNavigationFactory getFactory()
 */
class ContentNavigationClient extends AbstractClient implements ContentNavigationClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentNavigationTypeTransfer|null
     */
    public function executeNavigationTypeByKey(string $contentKey, string $localeName): ?ContentNavigationTypeTransfer
    {
        return $this->getFactory()
            ->createContentNavigationTypeMapper()
            ->executeNavigationTypeByKey($contentKey, $localeName);
    }
}
