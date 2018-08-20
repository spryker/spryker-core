<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\RestRequestValidator;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class RestRequestValidatorConfig extends AbstractBundleConfig
{
    const VALIDATION_CACHE_FILENAME_PATTERN = '/src/Generated/Glue/Validator/validation.cache';
}
