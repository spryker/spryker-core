<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business;

interface DevelopmentFacadeInterface
{

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function fixCodeStyle($bundle = null, array $options = []);

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function checkCodeStyle($bundle = null, array $options = []);

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function runTest($bundle, array $options = []);

    /**
     * @param string|null $bundle
     *
     * @return void
     */
    public function runPhpMd($bundle);

    /**
     * @param string $bundle
     * @param string $toBundle
     *
     * @return void
     */
    public function createBridge($bundle, $toBundle);

}
