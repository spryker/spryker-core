<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Business;

interface UrlStorageFacadeInterface
{
    /**
     * @api
     *
     * @param array $urlIds
     *
     * @return void
     */
    public function publishUrl(array $urlIds);

    /**
     * @api
     *
     * @param array $urlIds
     *
     * @return void
     */
    public function unpublishUrl(array $urlIds);

    /**
     * @api
     *
     * @param array $redirectIds
     *
     * @return void
     */
    public function publishRedirect(array $redirectIds);

    /**
     * @api
     *
     * @param array $redirectIds
     *
     * @return void
     */
    public function unpublishRedirect(array $redirectIds);
}
