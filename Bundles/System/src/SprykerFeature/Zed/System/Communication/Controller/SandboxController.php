<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\System\Communication\Controller;

use Exception;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerEngine\Zed\Kernel\Business\FacadeLocator;
use Symfony\Component\HttpFoundation\Request;

class SandboxController extends AbstractController
{

    public function patternAction()
    {
        $classNameGenerator = $this->newClassnameGenerator();
        $fileList = $this->createFilelist();

        foreach ($fileList as $filePath) {
            if ($this->excludePath($filePath)) {
                continue;
            }

            $className = $classNameGenerator->extractClassNameFromPath($filePath);
        }
    }

    /**
     * @return array
     */
    protected function createFilelist()
    {
        $directoryHelper = new \SprykerFeature_Zed_Library_Helper_Directory();
        $classMap1 = $directoryHelper->getFiles(APPLICATION_SOURCE_DIR . '/' . $this->getNamespaceProject() . '/');
        $classMap2 = $directoryHelper->getFiles(APPLICATION_VENDOR_DIR . '/spryker/');

        return array_merge($classMap1, $classMap2);
    }

    protected function newClassnameGenerator()
    {
        return new \SprykerFeature\Zed\Library\CodeGenerator\ClassnameGenerator();
    }

    /**
     * @param Request $request
     *
     * @throws Exception
     */
    public function configAction(Request $request)
    {
        $configClass = $request->query->get('class', 'XXX');
        $masterKey = $request->query->get('key', 'XXX');

        $config = Config::get($masterKey);
        echo '<pre>';
        echo "namespace SprykerFeature\\Shared\\$configClass;
        interface {$configClass}Config
        {";
        echo '<br/>';

        $keys = array_keys($config);
        foreach ($keys as $key) {
            $var = $this->createVar($masterKey, $key);
            echo 'const ' . $var . " = '" . $var . "';<br />";
        }

        echo '
        }';

        echo '<br /><br />';
        foreach ($keys as $key) {
            $v = $config->$key;

            if (is_string($v)) {
                $v = "'$v'";
            }
            if (is_bool($v)) {
                $v = $v ? 'true' : 'false';
            }
            if (is_array($v)) {
                $v = 'ARRAY';
            }

            echo '$config[' . $configClass . 'Config::' . $this->createVar($masterKey, $key) . "] = $v;<br />";
        }
        echo '<br /><br />';
        foreach ($keys as $key) {
            echo 'Config::get(' . $configClass . 'Config::' . $this->createVar($masterKey, $key) . ')<br />';
        }

        die;
    }

    /**
     * @param string $masterKey
     * @param string $key
     *
     * @return string
     */
    protected function createVar($masterKey, $key)
    {
        return str_replace('-', '_', strtoupper($masterKey . '_' . $key));
    }

    public function listAction()
    {
        $helper = new \SprykerFeature_Zed_Library_Helper_Directory();
        $files = $helper->getFiles(APPLICATION_VENDOR_DIR . '/spryker/', ['SprykerFeature/Yves/', 'SprykerFeature/Zed/'], 'Config.php');
        $configFiles = [];
        foreach ($files as $file) {

            $class = $this->getClassName($file);

            if (!empty($class)) {
                $ns = str_replace('Config', '', $class);
                $refl = new \ReflectionClass('\SprykerFeature\\Shared\\' . $ns . '\\' . $class);
                if ($refl->implementsInterface('\SprykerFeature\Shared\Library\ConfigInterface')) {
                    $configFiles[$file] = $refl;
                }
            }
        }

        $data = [];
        foreach ($configFiles as $path => $refl) {
            /* @var \ReflectionClass $refl */

            $consts = $refl->getConstants();
            foreach ($consts as $constName => $constKey) {
                $dataItem = [];

                $dataItem['namespace'] = $refl->getNamespaceName();

                $dataItem['configKey'] = $constName;

                if (Config::hasValue($constKey)) {
                    $dataItem['configData'] = print_r(Config::get($constKey), true);
                } else {
                    $dataItem['configData'] = 'missing';
                }

                $data[] = $dataItem;
            }
        }

        echo '<table border="1">';
        foreach ($data as $dataItem) {
            $exppl = explode('\\', $dataItem['namespace']);
            $ns = end($exppl);
            echo '<tr>';
            echo '<td>' . $dataItem['namespace'] . '</td>';
            echo '<td>' . $dataItem['configKey'] . '</td>';
            echo '<td>Config::get(' . $ns . 'Config::' . $dataItem['configKey'] . ')</td>';
            echo '<td><pre>' . $dataItem['configData'] . '</pre></td>';
            echo '</tr>';
        }
        echo '</table>';

        echo '<pre>';
        var_dump('STOP');
        echo '<hr>';
        echo __FILE__ . ' ' . __LINE__;
        die;

    }

    protected function getClassName($path)
    {
        $code = file_get_contents($path);
        $this->openFiles[$path] = $code;
        $tokens = token_get_all($code);

        $classDetected = false;
        foreach ($tokens as $token) {
            if (empty($token[1])) {
                continue;
            }
            $value = trim($token[1]);
            if ($value === 'interface') {
                $classDetected = true;
                continue;
            }

            if ($classDetected && !empty($value)) {
                return $value;
            }
        }

        return false;
    }

    public function testAction()
    {

        $facade = (new FacadeLocator())->locate('Discount');
//        echo '<pre>';
//        var_dump($facade);
//        echo __CLASS__;
//        echo '<br/>';
//        echo __LINE__;
//        echo '<pre>';
//        die;
    }

}
