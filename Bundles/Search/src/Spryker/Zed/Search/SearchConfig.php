<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Shared\Application\ApplicationConstants;

class SearchConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getElasticaDocumentType()
    {
        return $this->get(ApplicationConstants::ELASTICA_PARAMETER__DOCUMENT_TYPE);
    }

}
