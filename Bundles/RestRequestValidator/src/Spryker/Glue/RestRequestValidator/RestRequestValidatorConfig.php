<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\RestRequestValidator\RestRequestValidatorConfig as RestRequestValidatorConfigShared;
use Symfony\Component\HttpFoundation\Request;

class RestRequestValidatorConfig extends AbstractBundleConfig
{
    public const RESPONSE_CODE_REQUEST_INVALID = '901';

    /**
     * @return string[]
     */
    public function getAvailableConstraintNamespaces(): array
    {
        return [
            '\\Symfony\\Component\\Validator\\Constraints\\',
        ];
    }

    /**
     * @return string[]
     */
    public function getAvailableMethods(): array
    {
        return [
            Request::METHOD_POST,
            Request::METHOD_PUT,
            Request::METHOD_PATCH,
        ];
    }

    /**
     * @return string
     */
    public function getValidationCacheFilenamePattern(): string
    {
        return APPLICATION_SOURCE_DIR . RestRequestValidatorConfigShared::VALIDATION_CACHE_FILENAME_PATTERN;
    }
}
