<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Query;

interface QueryInterface
{

    /**
     * @param array $requestParameters
     *
     * @return mixed
     */
    public function getSearchQuery(array $requestParameters = []);

}
