<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitize\Dependency\External;

use voku\helper\AntiXSS;

class UtilSanitizeToVokuAntiXssAdapter implements UtilSanitizeToXssSanitizeInterface
{
    /**
     * @var \voku\helper\AntiXSS
     */
    protected $vokuAntiXss;

    public function __construct()
    {
        $this->vokuAntiXss = new AntiXSS();
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
        $this->vokuAntiXss->removeEvilAttributes($allowedAttributes);
        $this->vokuAntiXss->removeEvilHtmlTags($allowedHtmlTags);

        return $this->vokuAntiXss->xss_clean($text);
    }
}
