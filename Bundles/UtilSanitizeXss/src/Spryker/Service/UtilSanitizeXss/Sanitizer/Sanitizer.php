<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitizeXss\Sanitizer;

use Spryker\Service\UtilSanitizeXss\Dependency\External\UtilSanitizeToXssSanitizeInterface;

class Sanitizer implements SanitizerInterface
{
    /**
     * @var \Spryker\Service\UtilSanitizeXss\Dependency\External\UtilSanitizeToXssSanitizeInterface
     */
    protected UtilSanitizeToXssSanitizeInterface $xssSanitizer;

    /**
     * @var list<\Spryker\Service\UtilSanitizeXss\Escaper\EscaperInterface>
     */
    protected array $escapers;

    /**
     * @param \Spryker\Service\UtilSanitizeXss\Dependency\External\UtilSanitizeToXssSanitizeInterface $xssSanitizer
     * @param list<\Spryker\Service\UtilSanitizeXss\Escaper\EscaperInterface> $escapers
     */
    public function __construct(
        UtilSanitizeToXssSanitizeInterface $xssSanitizer,
        array $escapers
    ) {
        $this->xssSanitizer = $xssSanitizer;
        $this->escapers = $escapers;
    }

    /**
     * @param string $text
     * @param list<string> $allowedAttributes
     * @param list<string> $allowedHtmlTags
     *
     * @return string
     */
    public function sanitize(string $text, array $allowedAttributes = [], array $allowedHtmlTags = []): string
    {
        $text = $this->executeEscape($text);

        $sanitizedText = $this->xssSanitizer->sanitize($text, $allowedAttributes, $allowedHtmlTags);

        return $this->executeRestore($sanitizedText);
    }

    /**
     * @param string $text
     *
     * @return string
     */
    protected function executeEscape(string $text): string
    {
        foreach ($this->escapers as $escaper) {
            $text = $escaper->escape($text);
        }

        return $text;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    protected function executeRestore(string $text): string
    {
        foreach ($this->escapers as $escaper) {
            $text = $escaper->restore($text);
        }

        return $text;
    }
}
