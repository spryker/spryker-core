<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\SearchPage\Business\Exception\DocumentAttributeDoesNotExistException;
use SprykerFeature\Shared\SearchPage\Dependency\DocumentAttributeInterface;

interface DocumentAttributeWriterInterface
{

    /**
     * @param DocumentAttributeInterface $documentAttribute
     *
     * @return int
     */
    public function createDocumentAttribute(DocumentAttributeInterface $documentAttribute);

    /**
     * @param DocumentAttributeInterface $documentAttribute
     *
     * @throws DocumentAttributeDoesNotExistException
     * @throws PropelException
     *
     * @return int
     */
    public function updateDocumentAttribute(DocumentAttributeInterface $documentAttribute);

}
