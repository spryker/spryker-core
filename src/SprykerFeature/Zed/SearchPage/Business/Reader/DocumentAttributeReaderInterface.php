<?php

namespace SprykerFeature\SearchPage\Business\Reader;

use SprykerFeature\Zed\SearchPage\Persistence\Propel\SpySearchDocumentAttribute;

interface DocumentAttributeReaderInterface
{

    /**
     * @param string $documentName
     * @param string $type
     *
     * @return bool
     */
    public function hasDocumentAttributeByNameAndType($documentName, $type);

    /**
     * @param $idDocumentAttribute
     *
     * @return SpySearchDocumentAttribute
     */
    public function getDocumentAttributeById($idDocumentAttribute);

    /**
     * @return bool
     */
    public function hasDocumentAttributes();
}
