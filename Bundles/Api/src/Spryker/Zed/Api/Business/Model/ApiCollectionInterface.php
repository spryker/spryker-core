<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model;

use Everon\Component\Collection\CollectionInterface;

interface ApiCollectionInterface extends CollectionInterface
{

    /**
     * @param bool $deep
     *
     * @return array
     */
    public function modifiedToArray($deep = false);

}
