<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Business;

interface UrlStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all urls with the given urlIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $urlIds
     *
     * @return void
     */
    public function publishUrl(array $urlIds);

    /**
     * Specification:
     * - Finds and deletes url storage entities with the given $urlIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $urlIds
     *
     * @return void
     */
    public function unpublishUrl(array $urlIds);

    /**
     * Specification:
     * - Queries all redirects with the given redirectIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $redirectIds
     *
     * @return void
     */
    public function publishRedirect(array $redirectIds);

    /**
     * Specification:
     * - Finds and deletes redirect storage entities with the given $redirectIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $redirectIds
     *
     * @return void
     */
    public function unpublishRedirect(array $redirectIds);
}
