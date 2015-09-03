<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business\Model;

class PropelSchema implements PropelSchemaInterface
{

    /**
     * @var PropelGroupedSchemaFinderInterface
     */
    private $finder;

    /**
     * @var PropelSchemaWriterInterface
     */
    private $writer;

    /**
     * @var PropelSchemaMergerInterface
     */
    private $merger;

    /**
     * @param PropelGroupedSchemaFinderInterface $finder
     * @param PropelSchemaWriterInterface $writer
     * @param PropelSchemaMergerInterface $merger
     */
    public function __construct(
        PropelGroupedSchemaFinderInterface $finder,
        PropelSchemaWriterInterface $writer,
        PropelSchemaMergerInterface $merger
    ) {
        $this->finder = $finder;
        $this->writer = $writer;
        $this->merger = $merger;
    }

    public function copy()
    {
        $schemaFiles = $this->finder->getGroupedSchemaFiles();

        foreach ($schemaFiles as $fileName => $groupedSchemas) {
            if ($this->needMerge($groupedSchemas)) {
                $content = $this->merger->merge($groupedSchemas);
            } else {
                $content = $this->getCurrentSchemaContent($groupedSchemas);
            }
            $this->writer->write($fileName, $content);
        }
    }

    /**
     * @param array $groupedSchemas
     *
     * @return bool
     */
    private function needMerge(array $groupedSchemas)
    {
        return (count($groupedSchemas) > 1);
    }

    /**
     * @param array $groupedSchemas
     *
     * @return string
     */
    private function getCurrentSchemaContent(array $groupedSchemas)
    {
        $schemaFile = current($groupedSchemas);

        return $schemaFile->getContents();
    }

}
