<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Mapper;

use Exception;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\ErrorTransfer;

class ExceptionToErrorMapper implements ExceptionToErrorMapperInterface
{
    /**
     * @var array<\Spryker\Zed\DynamicEntity\Persistence\Mapper\DatabaseExceptionToErrorMapperInterface>
     */
    protected array $databaseExceptionToErrorMappers;

    /**
     * @var string
     */
    protected const RELATION_CHAIN_PLACEHOLDER = '%s.%s';

    /**
     * @param array<\Spryker\Zed\DynamicEntity\Persistence\Mapper\DatabaseExceptionToErrorMapperInterface> $databaseExceptionToErrorMappers
     */
    public function __construct(array $databaseExceptionToErrorMappers)
    {
        $this->databaseExceptionToErrorMappers = $databaseExceptionToErrorMappers;
    }

    /**
     * @param \Exception $exception
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer|null
     */
    public function map(
        Exception $exception,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $errorPath
    ): ?ErrorTransfer {
        foreach ($this->databaseExceptionToErrorMappers as $databaseExceptionToErrorMapper) {
            if (!$databaseExceptionToErrorMapper->isApplicable($exception)) {
                continue;
            }

            $errorPath = $this->appendMappedExceptionToKeyToErrorPath(
                $errorPath,
                $databaseExceptionToErrorMapper->mapExceptionToErrorMessage($exception),
            );
            $errorKey = $databaseExceptionToErrorMapper->getErrorGlossaryKey();
            $errorDetails = $databaseExceptionToErrorMapper->getErrorGlossaryParams($errorPath);

            return (new ErrorTransfer())
                ->setEntityIdentifier($dynamicEntityConfigurationTransfer->getTableAliasOrFail())
                ->setMessage($errorKey)
                ->setParameters($errorDetails);
        }

        return null;
    }

    /**
     * @param string $errorPath
     * @param string|null $duplicatedKey
     *
     * @return string
     */
    protected function appendMappedExceptionToKeyToErrorPath(string $errorPath, ?string $duplicatedKey): string
    {
        if ($duplicatedKey === null) {
            return $errorPath;
        }

        return sprintf(static::RELATION_CHAIN_PLACEHOLDER, $errorPath, $duplicatedKey);
    }
}
