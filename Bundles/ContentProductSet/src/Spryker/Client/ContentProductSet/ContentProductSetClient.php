<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProductSet;

use Generated\Shared\Transfer\ContentProductSetTypeTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ContentProductSet\ContentProductSetFactory getFactory()
 */
class ContentProductSetClient extends AbstractClient implements ContentProductSetClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductSetTypeTransfer|null
     */
    public function executeProductSetTypeByKey(string $contentKey, string $localeName): ?ContentProductSetTypeTransfer
    {
        return $this->getFactory()
            ->createContentProductSetTypeMapper()
            ->executeProductSetTypeByKey($contentKey, $localeName);
    }
}
