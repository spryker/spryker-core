<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Mapper\PostgreSql;

use Exception;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;
use Spryker\Zed\DynamicEntity\Persistence\Mapper\DatabaseExceptionToErrorMapperInterface;

class DuplicateKeyExceptionToErrorMapper implements DatabaseExceptionToErrorMapperInterface
{
    /**
     * @var string
     */
    protected const ERROR_CODE_DUPLICATE_KEY = '23505';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_ENTITY_NOT_PERSISTED_DUPLICATE_ENTRY = 'dynamic_entity.validation.persistence_failed_duplicate_entry';

    /**
     * @var string
     */
    protected const DUPLICATED_KEY_REGEX = '/DETAIL:  Key \((.*?)\)/';

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

        return $code === static::ERROR_CODE_DUPLICATE_KEY;
    }

    /**
     * @return string
     */
    public function getErrorGlossaryKey(): string
    {
        return static::GLOSSARY_KEY_ERROR_ENTITY_NOT_PERSISTED_DUPLICATE_ENTRY;
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, string>
     */
    public function getErrorGlossaryParams(array $params): array
    {
        return [
            DynamicEntityConfig::ERROR_PATH => $params[static::ERROR_PATH],
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

        if (preg_match(static::DUPLICATED_KEY_REGEX, $previousException->getMessage(), $matches)) {
            return $matches[1];
        }

        return null;
    }
}
