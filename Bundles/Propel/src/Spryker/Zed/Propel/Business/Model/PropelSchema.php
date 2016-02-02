<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Business\Model;

class PropelSchema implements PropelSchemaInterface
{

    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface
     */
    private $finder;

    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelSchemaWriterInterface
     */
    private $writer;

    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelSchemaMergerInterface
     */
    private $merger;

    /**
     * @param \Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface $finder
     * @param \Spryker\Zed\Propel\Business\Model\PropelSchemaWriterInterface $writer
     * @param \Spryker\Zed\Propel\Business\Model\PropelSchemaMergerInterface $merger
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

    /**
     * @return void
     */
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
