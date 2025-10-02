<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Transformer;

use ArrayObject;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @implements \Symfony\Component\Form\DataTransformerInterface<\ArrayObject<int, \Generated\Shared\Transfer\UrlTransfer>|null, \ArrayObject<int, \Generated\Shared\Transfer\UrlTransfer>>
 */
class MerchantProfileUrlCollectionDataTransformer implements DataTransformerInterface
{
    /**
     * @param mixed|array<\Generated\Shared\Transfer\UrlTransfer> $value
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\UrlTransfer>
     */
    public function transform($value): ArrayObject
    {
        $merchantProfileUrlCollection = new ArrayObject();
        if (!$value || !is_iterable($value)) {
            return $merchantProfileUrlCollection;
        }

        foreach ($value as $urlTransfer) {
            $url = $urlTransfer->getUrl();

            if (!$url) {
                continue;
            }

            $urlPrefix = $urlTransfer->getUrlPrefix() ?? '';
            $url = preg_replace('#^' . $urlPrefix . '#i', '', $url);
            $urlTransfer->setUrl($url);
            $merchantProfileUrlCollection->append($urlTransfer);
        }

        return $merchantProfileUrlCollection;
    }

    /**
     * @param mixed|array<\Generated\Shared\Transfer\UrlTransfer> $value
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\UrlTransfer>
     */
    public function reverseTransform($value): ArrayObject
    {
        $merchantProfileUrlCollection = new ArrayObject();
        if (!$value || !is_iterable($value)) {
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
     * @param string|null $url
     * @param string $urlPrefix
     *
     * @return string
     */
    protected function getUrlWithPrefix(?string $url, string $urlPrefix): string
    {
        if (!$url) {
            return $urlPrefix;
        }

        $url = preg_replace('#^/#', '', $url);

        return $urlPrefix . $url;
    }

    /**
     * @param string|null $url
     * @param string $urlPrefix
     *
     * @return bool
     */
    protected function hasUrlPrefix(?string $url, string $urlPrefix): bool
    {
        if (!$url) {
            return false;
        }

        return (bool)preg_match('#^' . $urlPrefix . '#i', $url);
    }
}
