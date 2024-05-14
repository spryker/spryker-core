<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Mapper\Postgresql;

use Exception;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;
use Spryker\Zed\DynamicEntity\Persistence\Mapper\DatabaseExceptionToErrorMapperInterface;

class NotNullViolationExceptionToErrorMapper implements DatabaseExceptionToErrorMapperInterface
{
    /**
     * @var string
     */
    protected const ERROR_CODE_NOT_NULL_VIOLATION = '23502';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_ENTITY_NOT_PERSISTED_NOT_NULL = 'dynamic_entity.validation.persistence_failed_not_nullable_field';

    /**
     * @var string
     */
    protected const NOT_NULL_KEY_REGEX = '/DETAIL:  Key \((.*?)\)/';

    /**
     * @param \Exception $exception
     *
     * @return bool
     */
    public function isApplicable(Exception $exception): bool
    {
        if ($exception->getPrevious() === null) {
            return false;
        }

        $code = (string)$exception->getPrevious()->getCode();

        return $code === static::ERROR_CODE_NOT_NULL_VIOLATION;
    }

    /**
     * @return string
     */
    public function getErrorGlossaryKey(): string
    {
        return static::GLOSSARY_KEY_ERROR_ENTITY_NOT_PERSISTED_NOT_NULL;
    }

    /**
     * @param string $errorPath
     *
     * @return array<string, string>
     */
    public function getErrorGlossaryParams(string $errorPath): array
    {
        return [
            DynamicEntityConfig::ERROR_PATH => $errorPath,
        ];
    }

    /**
     * @param \Exception $exception
     *
     * @return string|null
     */
    public function mapExceptionToErrorMessage(Exception $exception): ?string
    {
        $previousException = $exception->getPrevious();
        if ($previousException === null) {
            return null;
        }

        if (preg_match(static::NOT_NULL_KEY_REGEX, $previousException->getMessage(), $matches)) {
            return $matches[1];
        }

        return null;
    }
}
