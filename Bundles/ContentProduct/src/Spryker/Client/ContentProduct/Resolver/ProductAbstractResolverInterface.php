<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Resolver;

use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;

interface ProductAbstractResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @throws \Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTermTransfer
     */
    public function resolve(ContentTypeContextTransfer $contentTypeContextTransfer): ContentProductAbstractListTermTransfer;
}
