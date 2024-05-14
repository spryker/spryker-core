<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Mapper\Mysql;

use Exception;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;
use Spryker\Zed\DynamicEntity\Persistence\Mapper\DatabaseExceptionToErrorMapperInterface;

class NotNullableExceptionToErrorMapper implements DatabaseExceptionToErrorMapperInterface
{
    /**
     * @var string
     */
    protected const ERROR_CODE_INTEGRITY_CONSTRAINT = '23000';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_ENTITY_NOT_PERSISTED_NOT_NULLABLE_FIELD = 'dynamic_entity.validation.persistence_failed_not_nullable_field';

    /**
     * @var string
     */
    protected const NOT_NULL_ENTRY_REGEX = '/Column \'(.*?)\' cannot be null$/';

    /**
     * @param \Exception $exception
     *
     * @return bool
     */
    public function isApplicable(Exception $exception): bool
    {
        $previousException = $exception->getPrevious();
        if ($previousException === null) {
            return false;
        }

        $code = (string)$previousException->getCode();
        $errorMatches = (bool)preg_match(static::NOT_NULL_ENTRY_REGEX, $previousException->getMessage(), $matches);

        return ($code === static::ERROR_CODE_INTEGRITY_CONSTRAINT && $errorMatches !== null);
    }

    /**
     * @return string
     */
    public function getErrorGlossaryKey(): string
    {
        return static::GLOSSARY_KEY_ERROR_ENTITY_NOT_PERSISTED_NOT_NULLABLE_FIELD;
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

        if (preg_match(static::NOT_NULL_ENTRY_REGEX, $previousException->getMessage(), $matches)) {
            return $matches[1];
        }

        return null;
    }
}
