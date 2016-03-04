<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Storage;

/**
 * Class AdapterInterface
 */

interface AdapterInterface
{

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config);

    /**
     * @return array
     */
    public function getConfig();

    /**
     * @return bool
     */
    public function getDebug();

    /**
     * @param bool $debug
     *
     * @return $this
     */
    public function setDebug($debug);

    /**
     * @return void
     */
    public function connect();

}
