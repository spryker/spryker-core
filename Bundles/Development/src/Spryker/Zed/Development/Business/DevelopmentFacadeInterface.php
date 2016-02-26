<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business;

interface DevelopmentFacadeInterface
{

    /**
     * @api
     *
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function checkCodeStyle($bundle = null, array $options = []);

    /**
     * @api
     *
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function runTest($bundle, array $options = []);

    /**
     * @api
     *
     * @param string|null $bundle
     *
     * @return void
     */
    public function runPhpMd($bundle);

    /**
     * @api
     *
     * @param string $bundle
     * @param string $toBundle
     *
     * @return void
     */
    public function createBridge($bundle, $toBundle);

}
