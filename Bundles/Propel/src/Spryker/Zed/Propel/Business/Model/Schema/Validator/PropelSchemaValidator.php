<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\Schema\Validator;

use ArrayObject;
use Generated\Shared\Transfer\SchemaValidationErrorTransfer;
use Generated\Shared\Transfer\SchemaValidationTransfer;
use SimpleXMLElement;
use Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface;
use Spryker\Zed\Propel\Dependency\Service\PropelToUtilTextServiceInterface;
use Symfony\Component\Finder\SplFileInfo;

class PropelSchemaValidator implements PropelSchemaValidatorInterface
{
    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\Propel\Dependency\Service\PropelToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @var \Generated\Shared\Transfer\SchemaValidationTransfer|null
     */
    protected $schemaValidationTransfer;

    /**
     * Format:
     * ```
     * [
     *     'foo_bar.schema.xml' => [
     *         'attribute_a',
     *         'attribute_b',
     *     ]
     * ]
     * ```
     *
     * @var array
     */
    protected $whiteListedTableAttributes;

    /**
     * @param \Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface $finder
     * @param \Spryker\Zed\Propel\Dependency\Service\PropelToUtilTextServiceInterface $utilTextService
     * @param array $whileListedTableAttributes
     */
    public function __construct(
        PropelGroupedSchemaFinderInterface $finder,
        PropelToUtilTextServiceInterface $utilTextService,
        array $whileListedTableAttributes = []
    ) {
        $this->finder = $finder;
        $this->utilTextService = $utilTextService;
        $this->whiteListedTableAttributes = $whileListedTableAttributes;
    }

    /**
     * @return \Generated\Shared\Transfer\SchemaValidationTransfer
     */
    public function validate(): SchemaValidationTransfer
    {
        $groupedSchemaFiles = $this->getSchemaFilesForValidation();

        foreach ($groupedSchemaFiles as $fileName => $schemaFiles) {
            $mergeTargetXmlElement = $this->createNewXml();
            $schemaXmlElements = $this->createXmlElements($schemaFiles);

            $this->validateSchema($mergeTargetXmlElement, $schemaXmlElements, $fileName);
        }

        return $this->getSchemaValidationTransfer();
    }

    /**
     * @return array
     */
    protected function getSchemaFilesForValidation(): array
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
     * @return \SimpleXMLElement
     */
    protected function createNewXml(): SimpleXMLElement
    {
        return new SimpleXMLElement('<database></database>');
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo[] $schemaFiles
     *
     * @return \ArrayObject|\SimpleXMLElement[]
     */
    protected function createXmlElements(array $schemaFiles): ArrayObject
    {
        $mergeSourceXmlElements = new ArrayObject();
        foreach ($schemaFiles as $schemaFile) {
            $mergeSourceXmlElements[] = $this->createXmlElement($schemaFile);
        }

        return $mergeSourceXmlElements;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $schemaFile
     *
     * @return \SimpleXMLElement
     */
    protected function createXmlElement(SplFileInfo $schemaFile): SimpleXMLElement
    {
        return new SimpleXMLElement($schemaFile->getContents());
    }

    /**
     * @param \SimpleXMLElement $mergeTargetXmlElement
     * @param \ArrayObject $schemaXmlElements
     * @param string $fileName
     *
     * @return void
     */
    protected function validateSchema(SimpleXMLElement $mergeTargetXmlElement, ArrayObject $schemaXmlElements, $fileName): void
    {
        foreach ($schemaXmlElements as $schemaXmlElement) {
            $mergeTargetXmlElement = $this->validateSchemasRecursive($mergeTargetXmlElement, $schemaXmlElement, $fileName);
        }
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     * @param \SimpleXMLElement $fromXmlElement
     * @param string $fileName
     *
     * @return \SimpleXMLElement
     */
    protected function validateSchemasRecursive(SimpleXMLElement $toXmlElement, SimpleXMLElement $fromXmlElement, $fileName): SimpleXMLElement
    {
        $toXmlElements = $this->retrieveToXmlElements($toXmlElement);

        foreach ($fromXmlElement->children() as $fromXmlChildTagName => $fromXmlChildElement) {
            $fromXmlElementName = $this->getElementName($fromXmlChildElement, $fromXmlChildTagName);
            $toXmlElementChild = $this->getToXmlElementChild(
                $toXmlElement,
                $toXmlElements,
                $fromXmlElementName,
                $fromXmlChildTagName,
                $fromXmlChildElement
            );
            $this->validateAttributes($toXmlElementChild, $fromXmlChildElement, $fileName);
            $this->validateSchemasRecursive($toXmlElementChild, $fromXmlChildElement, $fileName);
        }

        return $toXmlElement;
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     * @param \ArrayObject $toXmlElements
     * @param string $fromXmlElementName
     * @param string $fromXmlChildTagName
     * @param \SimpleXMLElement $fromXmlChildElement
     *
     * @return \SimpleXMLElement
     */
    protected function getToXmlElementChild(SimpleXMLElement $toXmlElement, ArrayObject $toXmlElements, string $fromXmlElementName, string $fromXmlChildTagName, SimpleXMLElement $fromXmlChildElement): SimpleXMLElement
    {
        if (isset($toXmlElements[$fromXmlElementName])) {
            return $toXmlElements[$fromXmlElementName];
        }

        return $toXmlElement->addChild($fromXmlChildTagName, $fromXmlChildElement);
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     *
     * @return \ArrayObject
     */
    protected function retrieveToXmlElements(SimpleXMLElement $toXmlElement): ArrayObject
    {
        $toXmlElements = new ArrayObject();
        $toXmlElementChildren = $toXmlElement->children();

        foreach ($toXmlElementChildren as $toXmlChildTagName => $toXmlChildElement) {
            $toXmlElementName = $this->getElementName($toXmlChildElement, $toXmlChildTagName);
            $toXmlElements[$toXmlElementName] = $toXmlChildElement;
        }

        return $toXmlElements;
    }

    /**
     * @param \SimpleXMLElement $fromXmlChildElement
     * @param string $tagName
     *
     * @return string
     */
    protected function getElementName(SimpleXMLElement $fromXmlChildElement, string $tagName): string
    {
        $elementName = (array)$fromXmlChildElement->attributes();
        $elementName = current($elementName);
        $elementName = $this->buildCombinedNameIfNeeded($elementName, $tagName);
        $elementName = $this->anonymizeNameIfNeeded($elementName);

        return $elementName;
    }

    /**
     * @param mixed $elementName
     * @param string $tagName
     *
     * @return mixed
     */
    protected function buildCombinedNameIfNeeded($elementName, string $tagName)
    {
        if (is_array($elementName) && isset($elementName['name'])) {
            $elementName = $tagName . '|' . $elementName['name'];
        }

        return $elementName;
    }

    /**
     * @param mixed $elementName
     *
     * @return string
     */
    protected function anonymizeNameIfNeeded($elementName): string
    {
        if (empty($elementName) || is_array($elementName)) {
            $elementName = 'anonymous_' . $this->utilTextService->generateRandomString(32);
        }

        return $elementName;
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     * @param \SimpleXMLElement $fromXmlElement
     * @param string $fileName
     *
     * @return \SimpleXMLElement
     */
    protected function validateAttributes(SimpleXMLElement $toXmlElement, SimpleXMLElement $fromXmlElement, string $fileName): SimpleXMLElement
    {
        foreach ($fromXmlElement->attributes() as $key => $value) {
            $this->validateAttribute($toXmlElement, $key, $value, $fileName);
            $this->addAttribute($toXmlElement, $key, $value);
        }

        return $toXmlElement;
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     * @param string $key
     * @param string $value
     * @param string $fileName
     *
     * @return void
     */
    protected function validateAttribute(SimpleXMLElement $toXmlElement, string $key, string $value, string $fileName): void
    {
        $toXmlAttributes = iterator_to_array($toXmlElement->attributes());

        if ($this->isAttributeValueChange($toXmlAttributes, $key, $value) && !$this->isWhiteListed($fileName, $key)) {
            $this->addError(sprintf(
                'The attribute "%s" in one of your "%s" files has currently "%s" as value, if you would run the schema merger, this value would be overwritten with "%s". This can have weird side effects!',
                $key,
                $fileName,
                (string)$toXmlAttributes[$key],
                $value
            ));
        }
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    protected function addAttribute(SimpleXMLElement $toXmlElement, string $key, string $value): void
    {
        $toXmlAttributes = iterator_to_array($toXmlElement->attributes());

        if (!isset($toXmlAttributes[$key])) {
            $toXmlElement->addAttribute($key, $value);
        }
    }

    /**
     * @param array $toXmlAttributes
     * @param string $key
     * @param string $value
     *
     * @return bool
     */
    protected function isAttributeValueChange(array $toXmlAttributes, string $key, string $value): bool
    {
        return (isset($toXmlAttributes[$key]) && (string)$toXmlAttributes[$key] !== $value);
    }

    /**
     * @param string $fileName
     * @param string $key
     *
     * @return bool
     */
    protected function isWhiteListed(string $fileName, string $key): bool
    {
        if (isset($this->whiteListedTableAttributes[$fileName]) && in_array($key, $this->whiteListedTableAttributes[$fileName])) {
            return true;
        }

        return false;
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
