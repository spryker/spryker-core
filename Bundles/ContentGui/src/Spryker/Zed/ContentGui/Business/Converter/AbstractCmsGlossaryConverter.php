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
    protected $htmlToTwigExpressionConverter;

    /**
     * @var \Spryker\Zed\ContentGui\Business\Converter\TwigExpressionConverterInterface
     */
    protected $twigExpressionToHtmlConverter;

    /**
     * @param \Spryker\Zed\ContentGui\Business\Converter\HtmlConverterInterface $htmlToTwigExpressionConverter
     * @param \Spryker\Zed\ContentGui\Business\Converter\TwigExpressionConverterInterface $twigExpressionToHtmlConverter
     */
    public function __construct(
        HtmlConverterInterface $htmlToTwigExpressionConverter,
        TwigExpressionConverterInterface $twigExpressionToHtmlConverter
    ) {
        $this->htmlToTwigExpressionConverter = $htmlToTwigExpressionConverter;
        $this->twigExpressionToHtmlConverter = $twigExpressionToHtmlConverter;
    }

    /**
     * @param string $html
     *
     * @return string
     */
    protected function convertTranslationTwigExpressionToHtml(string $html): string
    {
        return $this->twigExpressionToHtmlConverter->convertTwigExpressionToHtml($html);
    }

    /**
     * @param string $html
     *
     * @return string
     */
    protected function convertTranslationHtmlToTwigExpression(string $html): string
    {
        return $this->htmlToTwigExpressionConverter->convertHtmlToTwigExpression($html);
    }
}
