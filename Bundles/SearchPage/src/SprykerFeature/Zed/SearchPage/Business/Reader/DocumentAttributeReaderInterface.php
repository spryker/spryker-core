<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Reader;

use Orm\Zed\SearchPage\Persistence\SpySearchDocumentAttribute;

interface DocumentAttributeReaderInterface
{

    /**
     * @param string $name
     * @param string $type
     *
     * @return bool
     */
    public function hasDocumentAttributeByNameAndType($name, $type);

    /**
     * @param int $idDocumentAttribute
     *
     * @return SpySearchDocumentAttribute
     */
    public function getDocumentAttributeById($idDocumentAttribute);

    /**
     * @return bool
     */
    public function hasDocumentAttributes();

}
