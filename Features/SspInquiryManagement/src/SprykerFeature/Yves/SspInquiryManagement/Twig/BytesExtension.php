<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Twig;

use Spryker\Shared\Twig\TwigExtension;
use Twig\TwigFilter;

class BytesExtension extends TwigExtension
{
    /**
     * @return array<\Twig\TwigFilter>
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_bytes', [$this, 'formatBytes']),
        ];
    }

    /**
     * @param int $bytes
     *
     * @return string
     */
    public function formatBytes(int $bytes): string
    {
        if ($bytes >= 1000 * 1000) {
            return round($bytes / (1000 * 1000), 2) . ' MB';
        } elseif ($bytes >= 1000) {
            return round($bytes / 1000, 2) . ' kB';
        } else {
            return $bytes . ' B';
        }
    }
}
