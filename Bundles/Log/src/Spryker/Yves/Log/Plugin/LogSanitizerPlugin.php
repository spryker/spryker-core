<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Log\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Log\LogFactory getFactory()
 */
class LogSanitizerPlugin extends AbstractPlugin
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function sanitize(array $data): array
    {
        return $this->getFactory()->createSanitizer()->sanitize($data);
    }
}
