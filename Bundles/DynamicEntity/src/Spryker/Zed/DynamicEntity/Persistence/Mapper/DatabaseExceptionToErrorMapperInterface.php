<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Mapper;

use Exception;

interface DatabaseExceptionToErrorMapperInterface
{
    /**
     * @var string
     */
    public const ERROR_PATH = 'errorPath';

    /**
     * @param \Exception $exception
     *
     * @return bool
     */
    public function isApplicable(Exception $exception): bool;

    /**
     * @return string
     */
    public function getErrorGlossaryKey(): string;

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, string>
     */
    public function getErrorGlossaryParams(array $params): array;

    /**
     * @param \Exception $exception
     *
     * @return string|null
     */
    public function mapExceptionToErrorMessage(Exception $exception): ?string;
}
