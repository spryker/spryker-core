<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\SearchPage\Business\Exception\DocumentAttributeDoesNotExistException;
use SprykerFeature\Zed\SearchPage\Business\Reader\DocumentAttributeReaderInterface;
use Orm\Zed\SearchPage\Persistence\SpySearchDocumentAttribute;
use SprykerFeature\Shared\SearchPage\Dependency\DocumentAttributeInterface;

class DocumentAttributeWriter implements DocumentAttributeWriterInterface
{

    /**
     * @var DocumentAttributeReaderInterface
     */
    private $documentAttributeReader;

    /**
     * @param DocumentAttributeReaderInterface $documentAttributeReader
     */
    public function __construct(DocumentAttributeReaderInterface $documentAttributeReader)
    {
        $this->documentAttributeReader = $documentAttributeReader;
    }

    /**
     * @param DocumentAttributeInterface $documentAttribute
     *
     * @return int
     */
    public function createDocumentAttribute(DocumentAttributeInterface $documentAttribute)
    {
        $documentAttributeEntity = new SpySearchDocumentAttribute();
        $documentAttributeEntity->setAttributeName($documentAttribute->getAttributeName());
        $documentAttributeEntity->setAttributeType($documentAttribute->getAttributeType());
        $documentAttributeEntity->save();

        return $documentAttributeEntity->getPrimaryKey();
    }

    /**
     * @param DocumentAttributeInterface $documentAttribute
     *
     * @throws DocumentAttributeDoesNotExistException
     * @throws PropelException
     *
     * @return int
     */
    public function updateDocumentAttribute(DocumentAttributeInterface $documentAttribute)
    {
        $idDocumentAttribute = $documentAttribute->getIdSearchDocumentAttribute();
        $documentAttributeEntity = $this->documentAttributeReader
            ->getDocumentAttributeById($idDocumentAttribute)
        ;

        if (is_null($documentAttributeEntity)) {
            throw new DocumentAttributeDoesNotExistException('The document-attribute does not exist in the DB');
        }
        $documentAttributeEntity->setAttributeName($documentAttribute->getAttributeName());
        $documentAttributeEntity->setAttributeType($documentAttribute->getAttributeType());
        $documentAttributeEntity->save();

        return $idDocumentAttribute;
    }

}
