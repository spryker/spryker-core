<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SearchConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getElasticaDocumentType()
    {
        return $this->get(ApplicationConstants::ELASTICA_PARAMETER__DOCUMENT_TYPE);
    }

    /**
     * @return array
     */
    public function getXmlIndexDefinitionDirectories()
    {
        return [
            APPLICATION_ROOT_DIR . '/config/Zed/search/',
        ];
    }

    /**
     * @return array
     */
    public function getJsonIndexDefinitionDirectories()
    {
        return [
            APPLICATION_ROOT_DIR . '/config/Zed/search/',
        ];
    }

    /**
     * @return string
     */
    public function getClassTargetDirectory()
    {
        return APPLICATION_SOURCE_DIR . '/Generated/Shared/Search/';
    }

}
