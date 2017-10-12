<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Table;

interface TableOptionsInterface
{
    /**
     * @param array $classesArray
     *
     * @return void
     */
    public function addClass(array $classesArray);

    /**
     * @return array
     */
    public function getTableClass();

    /**
     * @return array
     */
    public function toArray();
}
