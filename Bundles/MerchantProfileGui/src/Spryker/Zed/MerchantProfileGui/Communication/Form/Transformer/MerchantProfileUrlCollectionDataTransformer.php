<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form\Transformer;

use ArrayObject;
use Symfony\Component\Form\DataTransformerInterface;

class MerchantProfileUrlCollectionDataTransformer implements DataTransformerInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlTransfer[] $value
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\UrlTransfer[]
     */
    public function transform($value)
    {
        $merchantProfileUrlCollection = new ArrayObject();
        if (empty($value)) {
            return $merchantProfileUrlCollection;
        }
        foreach ($value as $urlTransfer) {
            $url = $urlTransfer->getUrl();
            $url = preg_replace('#^' . $urlTransfer->getUrlPrefix() . '#i', '', $url);
            $urlTransfer->setUrl($url);
            $merchantProfileUrlCollection->append($urlTransfer);
        }

        return $merchantProfileUrlCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer[] $value
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\UrlTransfer[]
     */
    public function reverseTransform($value)
    {
        $merchantProfileUrlCollection = new ArrayObject();
        if (empty($value)) {
            return $merchantProfileUrlCollection;
        }
        foreach ($value as $urlTransfer) {
            $urlPrefix = $urlTransfer->getUrlPrefix();
            $url = $urlTransfer->getUrl();
            if ($urlPrefix === null || preg_match('#^' . $urlPrefix . '#i', $url) > 0) {
                $merchantProfileUrlCollection->append($urlTransfer);
                continue;
            }
            $url = preg_replace('#^/#', '', $url);
            $urlWithPrefix = $urlPrefix . $url;
            $urlTransfer->setUrl($urlWithPrefix);
            $merchantProfileUrlCollection->append($urlTransfer);
        }

        return $merchantProfileUrlCollection;
    }
}
