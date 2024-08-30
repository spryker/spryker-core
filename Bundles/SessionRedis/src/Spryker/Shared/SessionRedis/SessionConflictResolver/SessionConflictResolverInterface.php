<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\SessionConflictResolver;

/**
 * Used for resolving session conflicts.
 */
interface SessionConflictResolverInterface
{
    /**
     * Specification:
     * - Compare and merge session data read before writing with the one read at the beginning of the current request
     * - Returns NULL if no data changed
     *
     * @param array<string> $savedSessionData
     * @param array<string> $defaultSessionData
     *
     * @return array<string>|null
     */
    public function resolveSessionConflicts(array $savedSessionData, array $defaultSessionData): ?array;

    /**
     * Specification:
     * - Checks if this plugin is applicable to execute.
     *
     * @param array<string> $savedSessionData
     * @param array<string> $defaultSessionData
     *
     * @return bool
     */
    public function isApplicable(array $savedSessionData, array $defaultSessionData): bool;
}
