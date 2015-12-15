<?php

namespace Spryker\Zed\Development\Business\Refactor\Propel;

use Spryker\Zed\Development\Business\Refactor\AbstractRefactor;
use Symfony\Component\Filesystem\Filesystem;

class FixIndentationOfIdMethodParameter extends AbstractRefactor
{

    /**
     * @var array
     */
    protected $directories = [];

    /**
     * @param array $directories
     */
    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    /**
     * @return void
     */
    public function refactor()
    {
        $schemaFiles = $this->getFiles($this->directories, '*schema.xml');

        $filesystem = new Filesystem();

        foreach ($schemaFiles as $schema) {
            $content = $schema->getContents();
            $tablePattern = '/<table(.*?)<\/table>/s';
            preg_match_all($tablePattern, $content, $tables);

            foreach ($tables[0] as $table) {
                $content = $this->fixIndentation($content, $table);
            }

            $filesystem->dumpFile($schema->getPathname(), $content);
        }
    }

    /**
     * @param string $content
     * @param string $table
     *
     * @return string
     */
    protected function fixIndentation($content, $table)
    {
        $pattern = '/    <id-method-parameter value="(.*?)"\/><\/table>/';
        if (preg_match($pattern, $table, $matches)) {
            $newParameter = '        <id-method-parameter value="' . $matches[1] . '"/>' . "\n" . '    </table>';

            $content = str_replace($matches[0], $newParameter, $content);
        }

        return $content;
    }

}
