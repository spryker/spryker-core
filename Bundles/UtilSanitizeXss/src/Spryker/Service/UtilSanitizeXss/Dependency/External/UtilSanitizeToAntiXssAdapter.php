<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitizeXss\Dependency\External;

use voku\helper\AntiXSS;

class UtilSanitizeToAntiXssAdapter implements UtilSanitizeToXssSanitizeInterface
{
    /**
     * @var \voku\helper\AntiXSS
     */
    protected $antiXss;

    public function __construct()
    {
        $this->antiXss = new AntiXSS();
    }

    /**
     * @param string $text
     * @param string[] $allowedAttributes
     * @param string[] $allowedHtmlTags
     *
     * @return string
     */
    public function sanitize(string $text, array $allowedAttributes = [], array $allowedHtmlTags = []): string
    {
        $this->antiXss->removeEvilAttributes($allowedAttributes);
        $this->antiXss->removeEvilHtmlTags($allowedHtmlTags);

        return $this->antiXss->xss_clean($text);
    }
}
