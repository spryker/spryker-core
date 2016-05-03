<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Oms;

interface OmsConstants
{

    const INITIAL_STATUS = 'new';

    /**
     * @deprecated Please use OmsConfig to get default process location
     */
    const DEFAULT_PROCESS_LOCATION = '/config/Zed/oms';

    const PROCESS_LOCATIONS = 'PROCESS_LOCATIONS';

    const NAME_CREDIT_MEMO_REFERENCE = 'CreditMemoReference';

}
