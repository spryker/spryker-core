<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Mapper\Postgresql;

use Exception;
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
     * @return array<string, string>
     */
    public function getErrorGlossaryParams(): array
    {
        return [];
    }
}
