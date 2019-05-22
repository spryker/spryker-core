<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter;

abstract class AbstractCmsGlossaryConverter
{
    /**
     * @var \Spryker\Zed\ContentGui\Business\Converter\HtmlConverterInterface
     */
    protected $htmlToShortCodeConverter;

    /**
     * @var \Spryker\Zed\ContentGui\Business\Converter\ShortCodeConverterInterface
     */
    protected $shortCodeToHtmlConverter;

    /**
     * @param \Spryker\Zed\ContentGui\Business\Converter\HtmlConverterInterface $htmlToShortCodeConverter
     * @param \Spryker\Zed\ContentGui\Business\Converter\ShortCodeConverterInterface $shortCodeToHtmlConverter
     */
    public function __construct(
        HtmlConverterInterface $htmlToShortCodeConverter,
        ShortCodeConverterInterface $shortCodeToHtmlConverter
    ) {
        $this->htmlToShortCodeConverter = $htmlToShortCodeConverter;
        $this->shortCodeToHtmlConverter = $shortCodeToHtmlConverter;
    }

    /**
     * @param string $html
     *
     * @return string
     */
    protected function convertTranslationShortCodeToHtml(string $html): string
    {
        return $this->shortCodeToHtmlConverter->replaceShortCode($html);
    }

    /**
     * @param string $html
     *
     * @return string
     */
    protected function convertTranslationHtmlToShortCode(string $html): string
    {
        return $this->htmlToShortCodeConverter->replaceWidget($html);
    }
}
