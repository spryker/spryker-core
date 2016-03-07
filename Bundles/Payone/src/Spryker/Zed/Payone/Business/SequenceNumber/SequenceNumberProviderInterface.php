<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\SequenceNumber;

interface SequenceNumberProviderInterface
{

    /**
     * @param string $transactionId
     *
     * @return int
     */
    public function getNextSequenceNumber($transactionId);

    /**
     * @param string $transactionId
     *
     * @return int
     */
    public function getCurrentSequenceNumber($transactionId);

}
