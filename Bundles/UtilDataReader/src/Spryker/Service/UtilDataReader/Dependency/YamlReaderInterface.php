<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader\Dependency;

interface YamlReaderInterface
{
    /**
     * @param string $fileName
     *
     * @return array
     */
    public function parse($fileName);
}
