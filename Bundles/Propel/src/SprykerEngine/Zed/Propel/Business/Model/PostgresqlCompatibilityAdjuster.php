<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerEngine\Zed\Propel\Business\Model;

use DOMDocument;
use DOMXPath;
use Symfony\Component\Finder\SplFileInfo;

class PostgresqlCompatibilityAdjuster implements PostgresqlCompatibilityAdjusterInterface
{

    /**
     * @var PropelSchemaFinderInterface
     */
    protected $schemaFinder;

    public function __construct(PropelSchemaFinderInterface $schemaFinder)
    {
        $this->schemaFinder = $schemaFinder;
    }

    /**
     * @return void
     */
    public function adjustSchemaFiles()
    {
        $files = $this->schemaFinder->getSchemaFiles();
        foreach ($files as $file) {
            $dom = $this->createDomDocumentFromFile($file);
            $domChanged = 0;
            $domChanged += $this->adjustForIdMethodParameter($dom);
            $domChanged += $this->adjustForNamedIndices($dom);
            if ($domChanged > 0) {
                echo "Writing to " . $file;
                $dom->save($file);
            }
        }
    }

    protected function adjustForNamedIndices(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);
        $nodeList = $xpath->query("//index[@name]|//unique[@name]|//foreign-key[@name]");
        $domChanged = 0;
        foreach ($nodeList as $node) {
            /** @var $node \DOMElement */
            $node->removeAttribute('name');
            $domChanged++;
        }

        return $domChanged;
    }

    protected function adjustForIdMethodParameter(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);
        $nodeList = $xpath->query("//column[@autoIncrement='true']");
        $domChanged = 0;
        foreach ($nodeList as $column) {
            /** @var $column \DOMElement */
            if ($xpath->query('id-method-parameter', $column->parentNode)->length > 0) {
                continue;
            }
            $tableName = $column->parentNode->attributes['name'];
            $sequenceName = $tableName->nodeValue . '_pk_seq';
            $idParamElement = $dom->createElement('id-method-parameter');
            $idParamElement->setAttribute('value', $sequenceName);

            $column->parentNode->appendChild($idParamElement);
            $domChanged++;
        }

        return $domChanged;
    }

    protected function createDomDocumentFromFile(SplFileInfo $file)
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = true;
        $dom->loadXML($file->getContents());

        return $dom;
    }
}
