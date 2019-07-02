<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ApplicationConfig extends AbstractBundleConfig
{
    /**
     * @const string
     */
    protected const HEADER_X_FRAME_OPTIONS = 'X-Frame-Options';

    /**
     * @const string
     */
    protected const HEADER_CONTENT_SECURITY_POLICY = 'Content-Security-Policy';

    /**
     * @const string
     */
    protected const HEADER_X_CONTENT_TYPE_OPTIONS = 'X-Content-Type-Options';

    /**
     * @const string
     */
    protected const HEADER_X_XSS_PROTECTION = 'X-XSS-Protection';

    /**
     * @const string
     */
    protected const HEADER_REFERRER_POLICY = 'Referrer-Policy';

    /**
     * @const string
     */
    protected const HEADER_FEATURE_POLICY = 'Feature-Policy';

    /**
     * @return array
     */
    public function getSecurityHeaders(): array
    {
        return [
            static::HEADER_X_FRAME_OPTIONS => 'SAMEORIGIN',
            static::HEADER_CONTENT_SECURITY_POLICY => 'frame-ancestors \'self\'',
            static::HEADER_X_CONTENT_TYPE_OPTIONS => 'nosniff',
            static::HEADER_X_XSS_PROTECTION => '1; mode=block',
            static::HEADER_REFERRER_POLICY => 'same-origin',
            static::HEADER_FEATURE_POLICY => '',
        ];
    }
}
