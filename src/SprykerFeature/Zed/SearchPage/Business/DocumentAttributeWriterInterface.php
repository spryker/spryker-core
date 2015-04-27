<?php

namespace SprykerFeature\SearchPage\Business;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\SearchPage\Business\Exception\DocumentAttributeDoesNotExistException;
use SprykerFeature\Shared\SearchPage\Dependency\DocumentAttributeInterface;

interface DocumentAttributeWriterInterface
{
    /**
     * @param DocumentAttributeInterface $documentAttribute
     *
     * @return int
     */
    public function create(DocumentAttributeInterface $documentAttribute);

    /**
     * @param DocumentAttributeInterface $documentAttribute
     *
     * @return int
     * @throws DocumentAttributeDoesNotExistException
     * @throws PropelException
     */
    public function update(DocumentAttributeInterface $documentAttribute);
}
