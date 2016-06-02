<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search;

use Spryker\Shared\Application\ApplicationConstants;

interface SearchConstants
{

    const ELASTICA_PARAMETER__HOST = ApplicationConstants::ELASTICA_PARAMETER__HOST;
    const ELASTICA_PARAMETER__TRANSPORT = ApplicationConstants::ELASTICA_PARAMETER__TRANSPORT;
    const ELASTICA_PARAMETER__PORT = ApplicationConstants::ELASTICA_PARAMETER__PORT;
    const ELASTICA_PARAMETER__AUTH_HEADER = ApplicationConstants::ELASTICA_PARAMETER__AUTH_HEADER;
    const ELASTICA_PARAMETER__INDEX_NAME = ApplicationConstants::ELASTICA_PARAMETER__INDEX_NAME;
    const ELASTICA_PARAMETER__DOCUMENT_TYPE = ApplicationConstants::ELASTICA_PARAMETER__DOCUMENT_TYPE;

}
