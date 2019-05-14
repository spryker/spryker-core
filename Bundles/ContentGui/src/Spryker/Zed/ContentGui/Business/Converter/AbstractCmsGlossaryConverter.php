<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter;

abstract class AbstractCmsGlossaryConverter
{
    /**
     * @var \Spryker\Zed\ContentGui\Business\Converter\ContentGuiConverterInterface
     */
    protected $htmlToShortCodeConverter;

    /**
     * @var \Spryker\Zed\ContentGui\Business\Converter\ContentGuiConverterInterface
     */
    protected $shortCodeToHtmlConverter;

    /**
     * @param \Spryker\Zed\ContentGui\Business\Converter\ContentGuiConverterInterface $htmlToShortCodeConverter
     * @param \Spryker\Zed\ContentGui\Business\Converter\ContentGuiConverterInterface $shortCodeToHtmlConverter
     */
    public function __construct(
        ContentGuiConverterInterface $htmlToShortCodeConverter,
        ContentGuiConverterInterface $shortCodeToHtmlConverter
    ) {
        $this->htmlToShortCodeConverter = $htmlToShortCodeConverter;
        $this->shortCodeToHtmlConverter = $shortCodeToHtmlConverter;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function convertTranslationShortCodeToHtml(string $string): string
    {
        return $this->shortCodeToHtmlConverter->convert($string);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function convertTranslationHtmlToShortCode(string $string): string
    {
        return $this->htmlToShortCodeConverter->convert($string);
    }
}
