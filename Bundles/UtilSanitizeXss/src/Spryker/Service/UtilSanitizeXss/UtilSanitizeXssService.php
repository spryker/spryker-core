<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitizeXss;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilSanitizeXss\UtilSanitizeXssServiceFactory getFactory()
 */
class UtilSanitizeXssService extends AbstractService implements UtilSanitizeXssServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $text
     * @param string[] $allowedAttributes
     * @param string[] $allowedHtmlTags
     *
     * @return string
     */
    public function sanitizeXss(string $text, array $allowedAttributes = [], array $allowedHtmlTags = []): string
    {
        return $this->getFactory()
            ->getXssSanitizer()
            ->sanitize($text, $allowedAttributes, $allowedHtmlTags);
    }
}
