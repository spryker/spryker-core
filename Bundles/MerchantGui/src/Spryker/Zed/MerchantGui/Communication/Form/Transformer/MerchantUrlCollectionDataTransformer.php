<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\Transformer;

use ArrayObject;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @implements \Symfony\Component\Form\DataTransformerInterface<\ArrayObject<int, \Generated\Shared\Transfer\UrlTransfer>, \ArrayObject<int, \Generated\Shared\Transfer\UrlTransfer>>
 */
class MerchantUrlCollectionDataTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\UrlTransfer>|null $value
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\UrlTransfer>
     */
    public function transform($value): ArrayObject
    {
        $merchantUrlCollection = new ArrayObject();
        if (!$value) {
            return $merchantUrlCollection;
        }
        foreach ($value as $urlTransfer) {
            $url = $urlTransfer->getUrl();
            $url = preg_replace('#^' . $urlTransfer->getUrlPrefix() . '#i', '', $url);
            $urlTransfer->setUrl($url);
            $merchantUrlCollection->append($urlTransfer);
        }

        return $merchantUrlCollection;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\UrlTransfer>|null $value
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\UrlTransfer>
     */
    public function reverseTransform($value): ArrayObject
    {
        $merchantUrlCollection = new ArrayObject();
        if (!$value) {
            return $merchantUrlCollection;
        }
        foreach ($value as $urlTransfer) {
            $urlPrefix = $urlTransfer->getUrlPrefix();
            $url = $urlTransfer->getUrl();
            if ($urlPrefix === null || preg_match('#^' . $urlPrefix . '#i', $url) > 0) {
                $merchantUrlCollection->append($urlTransfer);

                continue;
            }
            $url = preg_replace('#^/#', '', $url);
            $urlWithPrefix = $urlPrefix . $url;
            $urlTransfer->setUrl($urlWithPrefix);
            $merchantUrlCollection->append($urlTransfer);
        }

        return $merchantUrlCollection;
    }
}
