<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Sitemap\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \Spryker\Yves\Sitemap\SitemapFactory getFactory()
 * @method \Spryker\Yves\Sitemap\SitemapConfig getConfig()
 */
class ViewController extends AbstractController
{
    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE_XML = 'application/xml';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE_TEXT = 'text/plain';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_DISPOSITION = 'Content-Disposition';

    /**
     * @var string
     */
    protected const SITEMAP_FILE_NOT_FOUND_MESSAGE = 'Sitemap not found.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sitemapFileName
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function indexAction(Request $request, string $sitemapFileName): StreamedResponse
    {
        $storeName = $this->getFactory()
            ->getStoreClient()
            ->getCurrentStore()
            ->getNameOrFail();

        $cachedSitemapStream = $this->getFactory()->createSitemapCacheReader()->readStream($sitemapFileName, $storeName);

        if ($cachedSitemapStream !== null) {
            return $this->getResponse($sitemapFileName, $cachedSitemapStream);
        }

        $fileStream = $this->getFactory()
            ->createSitemapReader()
            ->readStream($sitemapFileName, $storeName);

        if ($fileStream === null) {
            return new StreamedResponse(function (): void {
                echo static::SITEMAP_FILE_NOT_FOUND_MESSAGE;
            }, 404, [
                static::HEADER_CONTENT_TYPE => static::HEADER_CONTENT_TYPE_TEXT,
            ]);
        }

        $this->getFactory()->createSitemapWriter()->writeStream($sitemapFileName, $storeName, $fileStream);
        rewind($fileStream);

        return $this->getResponse($sitemapFileName, $fileStream);
    }

    /**
     * @param string $sitemapFileName
     * @param mixed $fileStream
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function getResponse(string $sitemapFileName, $fileStream): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($fileStream): void {
            echo stream_get_contents($fileStream);
        });

        $response->headers->set(static::HEADER_CONTENT_TYPE, static::HEADER_CONTENT_TYPE_XML);
        $disposition = $response->headers
            ->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $sitemapFileName);
        $response->headers->set(static::HEADER_CONTENT_DISPOSITION, $disposition);

        return $response;
    }
}
