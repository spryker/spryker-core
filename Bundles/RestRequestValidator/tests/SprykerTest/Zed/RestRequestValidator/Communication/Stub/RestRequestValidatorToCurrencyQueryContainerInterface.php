<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Communication\Stub;

interface RestRequestValidatorToCurrencyQueryContainerInterface
{
    /**
     * @param string $isoCode
     *
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery
     */
    public function queryCurrencyByIsoCode(string $isoCode);
}
