<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Resolver;

use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException;
use Spryker\Shared\ContentProduct\ContentProductConfig;

class ProductAbstractResolver implements ProductAbstractResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @throws \Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTermTransfer
     */
    public function resolve(ContentTypeContextTransfer $contentTypeContextTransfer): ContentProductAbstractListTermTransfer
    {
        if ($contentTypeContextTransfer->getTerm() !== ContentProductConfig::CONTENT_TERM_PRODUCT_ABSTRACT_LIST) {
            throw new InvalidProductAbstractListTypeException(
                sprintf('There is no ContentProductAbstractList Term which can work with the term %s.', $contentTypeContextTransfer->getTerm())
            );
        }

        $contentProductAbstractListTermTransfer = new ContentProductAbstractListTermTransfer();
        $contentProductAbstractListTermTransfer->fromArray($contentTypeContextTransfer->getParameters());

        return $contentProductAbstractListTermTransfer;
    }
}
