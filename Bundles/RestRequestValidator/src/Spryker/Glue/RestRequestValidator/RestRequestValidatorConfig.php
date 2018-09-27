<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\RestRequestValidator\RestRequestValidatorConfig as RestRequestValidatorConfigShared;
use Symfony\Component\HttpFoundation\Request;

class RestRequestValidatorConfig extends AbstractBundleConfig
{
    public const RESPONSE_CODE_REQUEST_INVALID = '901';

    protected const CONSTRAINTS_NAMESPACE_SYMFONY_COMPONENT_VALIDATOR = 'Symfony\\Component\\Validator\\Constraints\\';
    protected const CONSTRAINTS_NAMESPACE_REST_REQUEST_VALIDATOR = 'Spryker\\Glue\\RestRequestValidator\\Constraints\\';
    protected const ALLOW_EXTRA_FIELDS = 'allowExtraFields';
    protected const ALLOW_EXTRA_FIELDS_VALUE = true;
    protected const GROUPS = 'groups';
    protected const GROUPS_VALUE = ['Default'];

    /**
     * @return string[]
     */
    public function getAvailableConstraintNamespaces(): array
    {
        return [
            static::CONSTRAINTS_NAMESPACE_SYMFONY_COMPONENT_VALIDATOR,
            static::CONSTRAINTS_NAMESPACE_REST_REQUEST_VALIDATOR,
        ];
    }

    /**
     * @return string[]
     */
    public function getHttpMethodsThatRequireValidation(): array
    {
        return [
            Request::METHOD_POST,
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

    /**
     * @return array
     */
    public function getConstraintCollectionConfig(): array
    {
        return [
            static::ALLOW_EXTRA_FIELDS => static::ALLOW_EXTRA_FIELDS_VALUE,
            static::GROUPS => static::GROUPS_VALUE,
        ];
    }
}
