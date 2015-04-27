<?php

namespace SprykerFeature\SearchPage\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\SearchPage\Business\DocumentAttributeWriterInterface;
use SprykerFeature\SearchPage\Business\Exception\DocumentAttributeDoesNotExistException;
use SprykerFeature\SearchPage\Business\Reader\DocumentAttributeReaderInterface;
use SprykerFeature\Shared\SearchPage\Dependency\DocumentAttributeInterface;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\SpySearchDocumentAttribute;

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
    public function create(DocumentAttributeInterface $documentAttribute)
    {
        $documentAttributeEntity = new SpySearchDocumentAttribute();
        $documentAttributeEntity->setAttributeName($documentAttribute->getAttributeName());
        $documentAttributeEntity->setDocumentType($documentAttribute->getDocumentType());
        $documentAttributeEntity->save();

        return $documentAttributeEntity->getPrimaryKey();
    }

    /**
     * @param DocumentAttributeInterface $documentAttribute
     *
     * @return int
     * @throws DocumentAttributeDoesNotExistException
     * @throws PropelException
     */
    public function update(DocumentAttributeInterface $documentAttribute)
    {
        $idDocumentAttribute = $documentAttribute->getIdSearchDocumentAttribute();
        $documentAttributeEntity = $this->documentAttributeReader
            ->getDocumentAttributeById($idDocumentAttribute)
        ;

        if (is_null($documentAttributeEntity)) {
            throw new DocumentAttributeDoesNotExistException('The document-attribute does not exist in the DB');
        }
        $documentAttributeEntity->setAttributeName($documentAttribute->getAttributeName());
        $documentAttributeEntity->setDocumentType($documentAttribute->getDocumentType());
        $documentAttributeEntity->save();

        return $idDocumentAttribute;
    }
}
