<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model;

interface FlysystemStorageInterface
{

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function getFlysystem();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getType();

}
