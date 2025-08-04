<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Exception;

use LogicException;

class SspModelImageFileStorageNameIsNotConfigured extends LogicException
{
    /**
     * @var string
     */
    protected const MESSAGE = 'The storage name for the SspModel image file is not configured. Please check the configuration in SelfServicePortalConfig::getSspModelImageFileStorageName().';

    public function __construct()
    {
        parent::__construct(static::MESSAGE);
    }
}
