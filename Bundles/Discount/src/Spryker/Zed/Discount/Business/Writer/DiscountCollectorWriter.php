<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountCollector;

class DiscountCollectorWriter extends AbstractWriter
{

    /**
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountCollector
     */
    public function create(DiscountCollectorTransfer $discountCollectorTransfer)
    {
        $discountCollectorEntity = new SpyDiscountCollector();
        $discountCollectorEntity->fromArray($discountCollectorTransfer->toArray());
        $discountCollectorEntity->save();

        return $discountCollectorEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountCollector
     */
    public function save(DiscountCollectorTransfer $discountCollectorTransfer)
    {
        if ($discountCollectorTransfer->getIdDiscountCollector() === null) {
            return $this->create($discountCollectorTransfer);
        }

        return $this->update($discountCollectorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountCollector
     */
    public function update(DiscountCollectorTransfer $discountCollectorTransfer)
    {
        $discountCollectorEntity = $this->getQueryContainer()
            ->queryDiscountCollectorById($discountCollectorTransfer->getIdDiscountCollector())
            ->findOne();

        $discountCollectorEntity->fromArray($discountCollectorTransfer->toArray());
        $discountCollectorEntity->save();

        return $discountCollectorEntity;
    }

}
