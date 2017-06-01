<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Dependency\Service;

interface ProductLabelToUtilDateTimeInterface
{

    /**
     * @param string $date
     *
     * @return string
     */
    public function formatDateTime($date);

    /**
     * @param string $dateTime
     *
     * @return \DateTime
     */
    public function fromString($dateTime);

}
