<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Url;

use Orm\Zed\Url\Persistence\SpyUrl;

abstract class AbstractUrlCreatorObserver
{

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return void
     */
    abstract public function update(SpyUrl $urlEntity);

}
