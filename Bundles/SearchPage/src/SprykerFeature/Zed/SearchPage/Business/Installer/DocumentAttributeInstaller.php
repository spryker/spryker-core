<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Installer;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\SearchPage\Business\Writer\DocumentAttributeWriterInterface;
use SprykerFeature\Zed\SearchPage\Business\Exception\DocumentAttributeAlreadyExistsException;
use SprykerFeature\Zed\SearchPage\Business\Reader\DocumentAttributeReaderInterface;
use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;

class DocumentAttributeInstaller extends AbstractInstaller
{

    const TYPE_FILTER = 'filter';
    const ATTRIBUTE_TYPE = 'type';
    const ATTRIBUTE_NAME = 'name';
    const SORT = 'sort';

    /**
     * @var LocatorLocatorInterface|AutoCompletion
     */
    private $locator;

    /**
     * @var DocumentAttributeWriterInterface
     */
    private $documentAttributeWriter;

    /**
     * @var DocumentAttributeReaderInterface
     */
    private $documentAttributeReader;

    /**
     * @param DocumentAttributeWriterInterface $documentAttributeWriter
     * @param DocumentAttributeReaderInterface $documentAttributeReader
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        DocumentAttributeWriterInterface $documentAttributeWriter,
        DocumentAttributeReaderInterface $documentAttributeReader,
        LocatorLocatorInterface $locator
    ) {
        $this->documentAttributeWriter = $documentAttributeWriter;
        $this->documentAttributeReader = $documentAttributeReader;
        $this->locator = $locator;
    }

    /**
     */
    public function install()
    {
        if ($this->documentAttributeReader->hasDocumentAttributes()) {
            $this->info('Skipping DocumentAttributeInstaller, cause attributes are already in DB.');

            return;
        }

        $documentAttributes = $this->getDocumentAttributes();
        $this->installDocumentAttributes($documentAttributes);
    }

    /**
     * @return array
     */
    private function getDocumentAttributes()
    {
        return [
            [
                self::ATTRIBUTE_NAME => 'string-facet',
                self::ATTRIBUTE_TYPE => self::TYPE_FILTER,
            ],
            [
                self::ATTRIBUTE_NAME => 'integer-facet',
                self::ATTRIBUTE_TYPE => self::TYPE_FILTER,
            ],
            [
                self::ATTRIBUTE_NAME => 'string-sort',
                self::ATTRIBUTE_TYPE => self::SORT,
            ],
            [
                self::ATTRIBUTE_NAME => 'integer-sort',
                self::ATTRIBUTE_TYPE => self::SORT,
            ],
        ];
    }

    /**
     * @param array $documentAttributes
     *
     * @throws DocumentAttributeAlreadyExistsException
     */
    private function installDocumentAttributes(array $documentAttributes)
    {
        foreach ($documentAttributes as $documentAttribute) {
            $attributeName = $documentAttribute[self::ATTRIBUTE_NAME];
            $attributeType = $documentAttribute[self::ATTRIBUTE_TYPE];

            $hasDocument = $this->documentAttributeReader
                ->hasDocumentAttributeByNameAndType($attributeName, $attributeType)
            ;

            if ($hasDocument) {
                throw new DocumentAttributeAlreadyExistsException(
                    sprintf(
                        'Document Attribute %s, %s already exists in Database',
                        $attributeName,
                        $attributeType
                    )
                );
            }

            $documentAttributeTransfer = new \Generated\Shared\Transfer\SearchPageDocumentAttributeTransfer();
            $documentAttributeTransfer->setAttributeName($attributeName);
            $documentAttributeTransfer->setAttributeType($attributeType);

            $this->documentAttributeWriter->createDocumentAttribute($documentAttributeTransfer);
        }
    }

}
