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
     * @param string $errorPath
     *
     * @return array<string, string>
     */
    public function getErrorGlossaryParams(string $errorPath): array;

    /**
     * @param \Exception $exception
     *
     * @return string|null
     */
    public function mapExceptionToErrorMessage(Exception $exception): ?string;
}
