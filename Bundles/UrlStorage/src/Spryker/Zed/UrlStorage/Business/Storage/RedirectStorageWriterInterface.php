<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Business\Storage;

interface RedirectStorageWriterInterface
{
    /**
     * @param array $redirectIds
     *
     * @return void
     */
    public function publish(array $redirectIds);

    /**
     * @param array $redirectIds
     *
     * @return void
     */
    public function unpublish(array $redirectIds);
}
