<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sitemap\Business\Generator;

use DOMAttr;
use DOMDocument;
use DOMElement;
use Generated\Shared\Transfer\SitemapUrlTransfer;
use Spryker\Zed\Sitemap\Dependency\Facade\SitemapToStoreFacadeInterface;

class XmlGenerator implements XmlGeneratorInterface
{
    /**
     * @var string
     */
    protected const TAG_URLSET = 'urlset';

    /**
     * @var string
     */
    protected const TAG_URL = 'url';

    /**
     * @var string
     */
    protected const TAG_LOC = 'loc';

    /**
     * @var string
     */
    protected const TAG_XHTML_LINK = 'xhtml:link';

    /**
     * @var string
     */
    protected const TAG_LASTMOD = 'lastmod';

    /**
     * @var string
     */
    protected const TAG_SITEMAPINDEX = 'sitemapindex';

    /**
     * @var string
     */
    protected const TAG_SITEMAP = 'sitemap';

    /**
     * @var string
     */
    protected const ATTRIBUTE_XMLNS = 'xmlns';

    /**
     * @var string
     */
    protected const ATTRIBUTE_XMLNS_XHTML = 'xmlns:xhtml';

    /**
     * @var string
     */
    protected const ATTRIBUTE_HREF = 'href';

    /**
     * @var string
     */
    protected const ATTRIBUTE_REL = 'rel';

    /**
     * @var string
     */
    protected const ATTRIBUTE_HREFLANG = 'hreflang';

    /**
     * @var string
     */
    protected const ATTRIBUTE_XMLNS_VALUE = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    /**
     * @var string
     */
    protected const ATTRIBUTE_XMLNS_XHTML_VALUE = 'http://www.w3.org/1999/xhtml';

    /**
     * @var string
     */
    protected const ATTRIBUTE_REL_VALUE = 'alternate';

    /**
     * @var string
     */
    protected const URL_PLACEHOLDER = '%s/%s%s';

    /**
     * @var string
     */
    protected const URL_PLACEHOLDER_WITHOUT_STORE_NAME = '%s%s';

    /**
     * @param \Spryker\Zed\Sitemap\Dependency\Facade\SitemapToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        protected SitemapToStoreFacadeInterface $storeFacade
    ) {
    }

    /**
     * @param array<\Generated\Shared\Transfer\SitemapUrlTransfer> $sitemapUrlTransfers
     * @param array<array<int, \Generated\Shared\Transfer\SitemapUrlTransfer>> $sitemapUrlTransfersGroupedByIdEntity
     * @param string $yvesHost
     *
     * @return string
     */
    public function generateSitemapXmlContent(
        array $sitemapUrlTransfers,
        array $sitemapUrlTransfersGroupedByIdEntity,
        string $yvesHost
    ): string {
        $domDocument = $this->createDomDocument();
        $urlsetElement = $domDocument->appendChild($this->createUrlsetElement($domDocument));

        foreach ($sitemapUrlTransfers as $sitemapUrlTransfer) {
            $urlsetElement->appendChild(
                $this->createUrlElement($domDocument, $sitemapUrlTransfer, $sitemapUrlTransfersGroupedByIdEntity, $yvesHost),
            );
        }

        return $domDocument->saveXML() ?: '';
    }

    /**
     * @param array<string> $sitemapFileNames
     * @param string $yvesHost
     *
     * @return string
     */
    public function generateSitemapIndexXmlContent(array $sitemapFileNames, string $yvesHost): string
    {
        $domDocument = $this->createDomDocument();
        $sitemapIndexElement = $domDocument->appendChild($this->createSitemapindexElement($domDocument));

        foreach ($sitemapFileNames as $sitemapFileName) {
            $sitemapElement = $domDocument->createElement(static::TAG_SITEMAP);
            $sitemapElement->appendChild($domDocument->createElement(static::TAG_LOC, $yvesHost . '/' . $sitemapFileName));
            $sitemapIndexElement->appendChild($sitemapElement);
        }

        return $domDocument->saveXML() ?: '';
    }

    /**
     * @return \DOMDocument
     */
    protected function createDomDocument(): DOMDocument
    {
        $domDocument = new DOMDocument('1.0', 'UTF-8');
        $domDocument->preserveWhiteSpace = false;
        $domDocument->formatOutput = true;

        return $domDocument;
    }

    /**
     * @param \DOMDocument $domDocument
     *
     * @return \DOMElement
     */
    protected function createSitemapindexElement(DOMDocument $domDocument): DOMElement
    {
        $sitemapindexElement = $domDocument->createElement(static::TAG_SITEMAPINDEX);
        $sitemapindexElement->setAttributeNode(
            $this->createAttribute($domDocument, static::ATTRIBUTE_XMLNS, static::ATTRIBUTE_XMLNS_VALUE),
        );

        return $sitemapindexElement;
    }

    /**
     * @param \DOMDocument $domDocument
     *
     * @return \DOMElement
     */
    protected function createUrlsetElement(DOMDocument $domDocument): DOMElement
    {
        $urlsetElement = $domDocument->createElement(static::TAG_URLSET);
        $urlsetElement->setAttributeNode(
            $this->createAttribute($domDocument, static::ATTRIBUTE_XMLNS, static::ATTRIBUTE_XMLNS_VALUE),
        );
        $urlsetElement->setAttributeNode(
            $this->createAttribute($domDocument, static::ATTRIBUTE_XMLNS_XHTML, static::ATTRIBUTE_XMLNS_XHTML_VALUE),
        );

        return $urlsetElement;
    }

    /**
     * @param \DOMDocument $domDocument
     * @param \Generated\Shared\Transfer\SitemapUrlTransfer $sitemapUrlTransfer
     * @param array<array<int, \Generated\Shared\Transfer\SitemapUrlTransfer>> $sitemapUrlTransfersGroupedByIdEntity
     * @param string $yvesHost
     *
     * @return \DOMElement
     */
    protected function createUrlElement(
        DOMDocument $domDocument,
        SitemapUrlTransfer $sitemapUrlTransfer,
        array $sitemapUrlTransfersGroupedByIdEntity,
        string $yvesHost
    ): DOMElement {
        $urlElement = $domDocument->createElement(static::TAG_URL);

        $urlElement->appendChild(
            $domDocument->createElement(static::TAG_LOC, $this->getUrl($sitemapUrlTransfer, $yvesHost)),
        );
        $urlElement->appendChild(
            $domDocument->createElement(static::TAG_LASTMOD, $sitemapUrlTransfer->getUpdatedAtOrFail()),
        );

        if (count($sitemapUrlTransfersGroupedByIdEntity[$sitemapUrlTransfer->getIdEntityOrFail()]) > 1) {
            foreach ($sitemapUrlTransfersGroupedByIdEntity[$sitemapUrlTransfer->getIdEntityOrFail()] as $alternateSitemapUrlTransfer) {
                $urlElement->appendChild($this->createXhtmlLinkElement($domDocument, $alternateSitemapUrlTransfer, $yvesHost));
            }
        }

        return $urlElement;
    }

    /**
     * @param \DOMDocument $domDocument
     * @param \Generated\Shared\Transfer\SitemapUrlTransfer $sitemapUrlTransfer
     * @param string $yvesHost
     *
     * @return \DOMElement
     */
    protected function createXhtmlLinkElement(
        DOMDocument $domDocument,
        SitemapUrlTransfer $sitemapUrlTransfer,
        string $yvesHost
    ): DOMElement {
        $xhtmlLinkElement = $domDocument->createElement(static::TAG_XHTML_LINK);

        $xhtmlLinkElement->setAttributeNode(
            $this->createAttribute($domDocument, static::ATTRIBUTE_HREF, $this->getUrl($sitemapUrlTransfer, $yvesHost)),
        );
        $xhtmlLinkElement->setAttributeNode(
            $this->createAttribute(
                $domDocument,
                static::ATTRIBUTE_HREFLANG,
                $this->convertLanguageCodeToHreflang($sitemapUrlTransfer->getLanguageCodeOrFail()),
            ),
        );
        $xhtmlLinkElement->setAttributeNode(
            $this->createAttribute($domDocument, static::ATTRIBUTE_REL, static::ATTRIBUTE_REL_VALUE),
        );

        return $xhtmlLinkElement;
    }

    /**
     * @param \DOMDocument $domDocument
     * @param string $attributeName
     * @param string $attributeValue
     *
     * @return \DOMAttr
     */
    protected function createAttribute(DOMDocument $domDocument, string $attributeName, string $attributeValue): DOMAttr
    {
        $domAttribute = $domDocument->createAttribute($attributeName);
        $domAttribute->value = $attributeValue;

        return $domAttribute;
    }

    /**
     * @param string $languageCode
     *
     * @return string
     */
    protected function convertLanguageCodeToHreflang(string $languageCode): string
    {
        return str_replace('_', '-', strtolower($languageCode));
    }

    /**
     * @param \Generated\Shared\Transfer\SitemapUrlTransfer $sitemapUrlTransfer
     * @param string $yvesHost
     *
     * @return string
     */
    protected function getUrl(SitemapUrlTransfer $sitemapUrlTransfer, string $yvesHost): string
    {
        if ($this->storeFacade->isDynamicStoreEnabled() !== true) {
            return sprintf(
                static::URL_PLACEHOLDER_WITHOUT_STORE_NAME,
                $yvesHost,
                htmlspecialchars($sitemapUrlTransfer->getUrlOrFail(), ENT_QUOTES, 'UTF-8'),
            );
        }

        return sprintf(
            static::URL_PLACEHOLDER,
            $yvesHost,
            $sitemapUrlTransfer->getStoreName(),
            htmlspecialchars($sitemapUrlTransfer->getUrlOrFail(), ENT_QUOTES, 'UTF-8'),
        );
    }
}
