<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
