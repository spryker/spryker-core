<?php

/**
 * This file is part of the Propel package - modified by Spryker Systems GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with the source code of the extended class.
 *
 * @license MIT License
 * @see https://github.com/propelorm/Propel2
 */

namespace Propel\Generator\Model;

use InvalidArgumentException;
use Propel\Common\Util\PathTrait;
use Propel\Generator\Builder\Util\PropelTemplate;
use Throwable;

/**
 * @deprecated Will be removed in the next major. Methods will be moved to the class that uses them.
 */
trait BehaviorTrait
{
    use PathTrait;

    /**
     * @var string
     */
    protected string $keyword_polyfill = 'Polyfill';

    /**
     * @var string
     */
    protected string $keyword_src = '/src/';

    /**
     * @var string
     */
    protected string $propel_path = '/vendor/propel/propel/src/Propel';

    /**
     * @var string
     */
    protected $dirname;

    /**
     * Use Propel simple templating system to render a PHP file using variables
     * passed as arguments. The template file name is relative to the behavior's
     * directory name.
     *
     * @param string $filename
     * @param array $vars
     * @param string|null $templatePath
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function renderTemplate(string $filename, array $vars = [], ?string $templatePath = null): string
    {
        if ($templatePath === null) {
            $dirname = $this->getOverridedTemplatePath();
            try {
                $templatePath = $this->getTemplatePath($dirname);
            } catch (Throwable $e) {
                $templatePath = $this->getTemplatePath($this->getDirname());
            }
        }

        $filePath = $templatePath . $filename;
        if (!file_exists($filePath)) {
            // try with '.php' at the end
            $filePath = $filePath . '.php';

            if (!file_exists($filePath)) {
                throw new InvalidArgumentException(sprintf(
                    'Template `%s` not found in `%s` directory',
                    $filename,
                    $templatePath,
                ));
            }
        }
        $template = new PropelTemplate();
        $template->setTemplateFile($filePath);
        $vars = array_merge($vars, ['behavior' => $this]);

        return $template->render($vars);
    }

    /**
     * @param string $templateDir
     *
     * @return string
     */
    protected function getOverridedTemplatePath(string $templateDir = ''): string
    {
        $path = $this->getDirname();
        $split = explode($this->keyword_polyfill, $path);
        if (count($split) > 1) {
            return APPLICATION_ROOT_DIR . $this->propel_path . end($split) . $templateDir;
        }

        return str_replace($this->keyword_src, $templateDir, $path) . DIRECTORY_SEPARATOR;
    }
}
