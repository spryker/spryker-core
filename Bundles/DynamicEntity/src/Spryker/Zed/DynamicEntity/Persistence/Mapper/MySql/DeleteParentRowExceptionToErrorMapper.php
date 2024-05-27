<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Mapper\MySql;

use Exception;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;
use Spryker\Zed\DynamicEntity\Persistence\Mapper\DatabaseExceptionToErrorMapperInterface;

class DeleteParentRowExceptionToErrorMapper implements DatabaseExceptionToErrorMapperInterface
{
    /**
     * @var string
     */
    protected const ERROR_CODE_INTEGRITY_CONSTRAINT = '23000';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_DELETE_FOREIGN_KEY_CONSTRAINT_FAILS = 'dynamic_entity.validation.delete_foreign_key_constraint_fails';

    /**
     * @var string
     */
    protected const FOREIGN_KEY_CONSTRAINT_FAILS = 'a foreign key constraint fails';

    /**
     * @var string
     */
    protected const CHILD_ENTITY_PATTERN = '%childEntity%';

    /**
     * @var string
     */
    protected const EXCEPTION = 'exception';

    /**
     * @var string
     */
    protected const REGEX = '/`([^`]+)`\.`([^`]+)`/';

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
        $errorMatches = (bool)strpos($previousException->getMessage(), static::FOREIGN_KEY_CONSTRAINT_FAILS) === true;

        return $code === static::ERROR_CODE_INTEGRITY_CONSTRAINT && $errorMatches === true;
    }

    /**
     * @return string
     */
    public function getErrorGlossaryKey(): string
    {
        return static::GLOSSARY_KEY_ERROR_DELETE_FOREIGN_KEY_CONSTRAINT_FAILS;
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
            static::CHILD_ENTITY_PATTERN => $this->getChildEntity($params),
        ];
    }

    /**
     * @param \Exception $exception
     *
     * @return string|null
     */
    public function mapExceptionToErrorMessage(Exception $exception): ?string
    {
        return null;
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return string
     */
    protected function getChildEntity(array $params): string
    {
        $exception = $params[static::EXCEPTION];
        $previousExceptionMessage = ($exception->getPrevious())->getMessage();
        preg_match(static::REGEX, $previousExceptionMessage, $matches);

        return $matches[2];
    }
}
