<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Development;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class DevelopmentConfig extends AbstractSharedConfig
{
    public const NAME_VISIBLE_VIOLATIONS = 'visible';
    public const NAME_IGNORED_VIOLATIONS = 'ignored';
    public const VIOLATION_FIELD_NAME_DESCRIPTION = 'description';
    public const VIOLATION_FIELD_NAME_RULESET = 'ruleset';
    public const VIOLATION_FIELD_NAME_RULE = 'rule';
    public const VIOLATION_FIELD_NAME_PRIORITY = 'priority';
    public const VIOLATION_FIELD_NAME_FILENAME = 'fileName';
}
