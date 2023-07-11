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
     * @param array<\Spryker\Zed\DynamicEntity\Persistence\Mapper\DatabaseExceptionToErrorMapperInterface> $databaseExceptionToErrorMappers
     */
    public function __construct(array $databaseExceptionToErrorMappers)
    {
        $this->databaseExceptionToErrorMappers = $databaseExceptionToErrorMappers;
    }

    /**
     * @param \Exception $exception
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer|null
     */
    public function map(Exception $exception, DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): ?ErrorTransfer
    {
        foreach ($this->databaseExceptionToErrorMappers as $databaseExceptionToErrorMapper) {
            if (!$databaseExceptionToErrorMapper->isApplicable($exception)) {
                continue;
            }

            $errorKey = $databaseExceptionToErrorMapper->getErrorGlossaryKey();
            $errorDetails = $databaseExceptionToErrorMapper->getErrorGlossaryParams();

            return (new ErrorTransfer())
                ->setEntityIdentifier($dynamicEntityConfigurationTransfer->getTableAliasOrFail())
                ->setMessage($errorKey)
                ->setParameters($errorDetails);
        }

        return null;
    }
}
