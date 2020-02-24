<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Transformer;

use ArrayObject;
use Symfony\Component\Form\DataTransformerInterface;

class MerchantProfileUrlCollectionDataTransformer implements DataTransformerInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlTransfer[] $value
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\UrlTransfer[]
     */
    public function transform($value): ArrayObject
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
    public function reverseTransform($value): ArrayObject
    {
        $merchantProfileUrlCollection = new ArrayObject();

        if (empty($value)) {
            return $merchantProfileUrlCollection;
        }

        foreach ($value as $urlTransfer) {
            $urlPrefix = $urlTransfer->getUrlPrefix();
            $url = $urlTransfer->getUrl();

            if ($urlPrefix === null || $this->hasUrlPrefix($url, $urlPrefix)) {
                $merchantProfileUrlCollection->append($urlTransfer);

                continue;
            }

            $urlWithPrefix = $this->getUrlWithPrefix($url, $urlPrefix);
            $urlTransfer->setUrl($urlWithPrefix);

            $merchantProfileUrlCollection->append($urlTransfer);
        }

        return $merchantProfileUrlCollection;
    }

    /**
     * @param string $url
     * @param string $urlPrefix
     *
     * @return string
     */
    protected function getUrlWithPrefix(string $url, string $urlPrefix): string
    {
        $url = preg_replace('#^/#', '', $url);

        return $urlPrefix . $url;
    }

    /**
     * @param string $url
     * @param string $urlPrefix
     *
     * @return bool
     */
    protected function hasUrlPrefix(string $url, string $urlPrefix): bool
    {
        return preg_match('#^' . $urlPrefix . '#i', $url) > 0;
    }
}
