<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerEngine\Zed\Propel\Business\Model;

use DOMDocument;
use DOMXPath;
use Symfony\Component\Finder\SplFileInfo;
use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\System\SystemConfig;
use Propel\Runtime\Propel;


class PostgresqlCompatibilityAdjuster implements PostgresqlCompatibilityAdjusterInterface
{

    /**
     * @var PropelSchemaFinderInterface
     */
    protected $schemaFinder;

    /**
     * @param PropelSchemaFinderInterface $schemaFinder
     */
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
                $dom->save($file);
            }
        }
    }

    /**
     * @return void
     */
    public function addMissingFunctions()
    {

        $connection = Propel::getConnection();

        $connection->exec("
            CREATE OR REPLACE FUNCTION text_add(
                IN  p_current text,
                IN  p_next    anyelement,
                OUT o_result  text )
            RETURNS text AS \$BODY$
            DECLARE

            BEGIN
                -- select row
                o_result := CASE WHEN p_current IS NULL THEN p_next::text
                                 WHEN p_next IS NULL THEN p_current::text
                                 ELSE coalesce( p_current, '' ) || ',' || coalesce( p_next::text, '' ) END;
            END;
            \$BODY$
            LANGUAGE plpgsql COST 1;

            DROP AGGREGATE IF EXISTS group_concat( anyelement );

            CREATE AGGREGATE group_concat( anyelement )
            (
                sfunc = text_add,
                stype = text
            );

        ");
    }

    /**
     * @param DOMDocument $dom
     *
     * @return int
     */
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

    /**
     * @param DOMDocument $dom
     *
     * @return int
     */
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

    /**
     * @param SplFileInfo $file
     *
     * @return DOMDocument
     */
    protected function createDomDocumentFromFile(SplFileInfo $file)
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = true;
        $dom->loadXML($file->getContents());

        return $dom;
    }
}
