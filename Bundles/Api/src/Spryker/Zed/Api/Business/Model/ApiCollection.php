<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model;

use Everon\Component\Collection\Collection;

class ApiCollection extends Collection implements ApiCollectionInterface
{

    /**
     * @param bool $deep
     *
     * @return array
     */
    public function toArray($deep = false)
    {
        $data = $this->getArrayableData();

        $result = [];
        /** @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer */
        foreach ($data as $transfer) {
            $result[] = $transfer->toArray($deep);
        }

        return $result;
    }

}
