<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote\Dependency\Client;

interface ConfigurableBundleCartNoteToQuoteClientInterface
{
    /**
     * @return string
     */
    public function getStorageStrategy();
}
