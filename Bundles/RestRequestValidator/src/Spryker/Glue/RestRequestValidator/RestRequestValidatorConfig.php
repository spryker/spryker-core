<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\RestRequestValidator\RestRequestValidatorConfig as RestRequestValidatorConfigShared;
use Symfony\Component\HttpFoundation\Request;

class RestRequestValidatorConfig extends AbstractBundleConfig
{
    public const RESPONSE_CODE_REQUEST_INVALID = '901';

    protected const CONSTRAINTS_NAMESPACE_SYMFONY_COMPONENT_VALIDATOR = 'Symfony\\Component\\Validator\\Constraints\\';
    protected const CONSTRAINTS_NAMESPACE_PROJECT_STORE_REST_REQUEST_VALIDATOR = '\\Glue\\RestRequestValidator%s\\Constraints\\';
    protected const CONSTRAINTS_NAMESPACE_REST_REQUEST_VALIDATOR = '\\Glue\\RestRequestValidator\\Constraints\\';

    /**
     * @return string[]
     */
    public function getAvailableConstraintNamespaces(): array
    {
        return array_merge($this->getProjectNamespaces(), $this->getCoreNamespaces());
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
    public function getConstraintCollectionOptions(): array
    {
        return [
            'allowExtraFields' => true,
            'groups' => ['Default'],
        ];
    }

    /**
     * @return array
     */
    private function getCoreNamespaces(): array
    {
        $coreConstraintNamespaces = [];

        foreach ($this->get(KernelConstants::CORE_NAMESPACES) as $coreNamespace) {
            $coreConstraintNamespaces[] = $coreNamespace . static::CONSTRAINTS_NAMESPACE_REST_REQUEST_VALIDATOR;
        }
        $coreConstraintNamespaces[] = static::CONSTRAINTS_NAMESPACE_SYMFONY_COMPONENT_VALIDATOR;

        return $coreConstraintNamespaces;
    }

    /**
     * @return array
     */
    private function getProjectNamespaces(): array
    {
        $projectConstraintNamespaces = [];

        foreach ($this->get(KernelConstants::PROJECT_NAMESPACES) as $projectNamespaces) {
            $projectConstraintNamespaces[] = $projectNamespaces .
                sprintf(static::CONSTRAINTS_NAMESPACE_PROJECT_STORE_REST_REQUEST_VALIDATOR, Store::getInstance()->getCurrencyIsoCode());
            $projectConstraintNamespaces[] = $projectNamespaces . static::CONSTRAINTS_NAMESPACE_REST_REQUEST_VALIDATOR;
        }

        return $projectConstraintNamespaces;
    }
}
