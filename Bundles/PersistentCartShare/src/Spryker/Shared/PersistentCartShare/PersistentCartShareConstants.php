<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PersistentCartShare;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface PersistentCartShareConstants
{
    public const RESOURCE_TYPE_QUOTE = 'quote';

    public const SHARE_OPTION_PREVIEW = 'PREVIEW';
    public const SHARE_OPTION_FULL_ACCESS = 'FULL_ACCESS';
    public const SHARE_OPTION_READ_ONLY = 'READ_ONLY';

    public const ID_QUOTE_PARAMETER = 'id_quote';
    public const PARAM_SHARE_OPTION = 'share_option';
}
