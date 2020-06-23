<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct;

use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ContentProduct\ContentProductFactory getFactory()
 */
class ContentProductClient extends AbstractClient implements ContentProductClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer|null
     */
    public function executeProductAbstractListTypeByKey(string $contentKey, string $localeName): ?ContentProductAbstractListTypeTransfer
    {
        return $this->getFactory()
            ->createContentProductAbstractListTypeMapper()
            ->executeProductAbstractListTypeByKey($contentKey, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @phpstan-return array<string, \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer>
     *
     * @param string[] $contentKeys
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer[]
     */
    public function executeProductAbstractListTypeByKeys(array $contentKeys, string $localeName): array
    {
        return $this->getFactory()
            ->createContentProductAbstractListTypeMapper()
            ->executeProductAbstractListTypeByKeys($contentKeys, $localeName);
    }
}
