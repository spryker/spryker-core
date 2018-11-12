<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\Schema\XmlValidator;

use Generated\Shared\Transfer\SchemaValidationErrorTransfer;
use Generated\Shared\Transfer\SchemaValidationTransfer;
use SimpleXMLElement;
use Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface;
use Spryker\Zed\Propel\PropelConfig;
use Symfony\Component\Finder\SplFileInfo;

class PropelSchemaXmlNameValidator implements PropelSchemaXmlValidatorInterface
{
    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface
     */
    protected $finder;

    /**
     * @var \Generated\Shared\Transfer\SchemaValidationTransfer|null
     */
    protected $schemaValidationTransfer;

    /**
     * @param \Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface $finder
     */
    public function __construct(PropelGroupedSchemaFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @return \Generated\Shared\Transfer\SchemaValidationTransfer
     */
    public function validate(): SchemaValidationTransfer
    {
        $filePaths = $this->getSchemaFiles();

        foreach ($this->findInvalidIdIdentifiersInFiles($filePaths) as $identifier) {
            $this->addError(sprintf(
                'There is a problem with %s . The identifier "%s" has a length beyond the maximum identifier length "%s". Your database will persist a truncated identifier leading to more problems!',
                key($identifier),
                reset($identifier),
                PropelConfig::POSTGRES_INDEX_NAME_MAX_LENGTH
            ));
        }

        return $this->getSchemaValidationTransfer();
    }

    /**
     * @return array
     */
    protected function getSchemaFiles(): array
    {
        $schemaFiles = $this->finder->getGroupedSchemaFiles();
        $filteredGroupedSchemas = [];
        foreach ($schemaFiles as $fileName => $groupedSchemas) {
            if (count($groupedSchemas) > 1) {
                $filteredGroupedSchemas[$fileName] = $groupedSchemas;
            }
        }

        return $filteredGroupedSchemas;
    }

    /**
     * @param array $files
     *
     * @return array
     */
    protected function findInvalidIdIdentifiersInFiles(array $files): array
    {
        $invalidIdIdentifiers = [];
        foreach ($files as $schema) {
            foreach ($schema as $file) {
                foreach ($this->findInvalidIdentifiers($file) as $invalidIdIdentifier) {
                    $invalidIdIdentifiers[] = $invalidIdIdentifier;
                }
            }
        }

        return $invalidIdIdentifiers;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $file
     *
     * @return array
     */
    protected function findInvalidIdentifiers(SplFileInfo $file): array
    {
        $xml = new SimpleXMLElement($file->getContents());
        $elements = array_merge(
            $xml->xpath('/database/table/index/@name'),
            $xml->xpath('/database/table/@name'),
            $xml->xpath('/database/table/unique/@name'),
            $xml->xpath('/database/table/foreign-key/@name'),
            $xml->xpath('/database/table/foreign-key/reference/@local'),
            $xml->xpath('/database/table/id-method-parameter/@value')
        );

        $fileNames = [];
        foreach ($elements as $element) {
            $attributeValue = $element->__toString();
            if ($this->isLongerThanIdentifierMaxLength($attributeValue)) {
                $fileNames[] = [
                    $file->getFilename() => $attributeValue,
                ];
            }
        }

        return $fileNames;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function isLongerThanIdentifierMaxLength(string $name): bool
    {
        return (mb_strlen($name) > PropelConfig::POSTGRES_INDEX_NAME_MAX_LENGTH);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function addError(string $message): void
    {
        $schemaValidationErrorTransfer = new SchemaValidationErrorTransfer();
        $schemaValidationErrorTransfer->setMessage($message);

        $schemaValidationTransfer = $this->getSchemaValidationTransfer();
        $schemaValidationTransfer->setIsSuccess(false);
        $schemaValidationTransfer->addValidationError($schemaValidationErrorTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\SchemaValidationTransfer
     */
    protected function getSchemaValidationTransfer(): SchemaValidationTransfer
    {
        if (!$this->schemaValidationTransfer) {
            $this->schemaValidationTransfer = new SchemaValidationTransfer();
            $this->schemaValidationTransfer->setIsSuccess(true);
        }

        return $this->schemaValidationTransfer;
    }
}
