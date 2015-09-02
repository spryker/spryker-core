<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\Finder\Finder;

class MapController extends AbstractController
{

    public function watchAction()
    {
        $file = new \SplFileInfo(APPLICATION_ROOT_DIR . '/.class_map');
        $mapLastChange = $file->getMTime();

        $c = 0;
        while (true) {
            $c++;
            if ($c > 15) {
                break;
            }

            $basePaths = [
                APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/',
                APPLICATION_SOURCE_DIR,
            ];

            foreach ($basePaths as $basePath) {
                $files = $this->createBasePath($basePath);
                foreach ($files as $file) {
                    /* @var $file \Symfony\Component\Finder\SplFileInfo */
                    if ($file->getMTime() > $mapLastChange) {
                        $this->createMap();

                        $file = new \SplFileInfo(APPLICATION_ROOT_DIR . '/.class_map');
                        $mapLastChange = $file->getMTime();
                    }
                }
            }

            sleep(1);
        }

        die('<pre><b>' . print_r('!!', true) . '</b>' . PHP_EOL . __CLASS__ . ' ' . __LINE__);
    }

    public function indexAction()
    {
        $map = $this->createMap();

        die('<pre><b>' . print_r(count($map), true) . '</b>' . PHP_EOL . __CLASS__ . ' ' . __LINE__);
    }

    /**
     * @param $basePath
     *
     * @return $this|Finder
     */
    protected function createBasePath($basePath)
    {
        $finder = (new Finder())->in($basePath)
            ->files()
            ->name('*.php')
            ->exclude(['Base', 'Map', 'Generated']);

        return $finder;
    }

    /**
     * @param $finder
     * @param $map
     *
     * @return mixed
     */
    protected function buildMap(Finder $finder, $map)
    {
        foreach ($finder->getIterator() as $file) {
            /* @var $file \Symfony\Component\Finder\SplFileInfo */

            $path = $file->getRelativePath() . '/' . $file->getFilename();
            $className = '\\' . str_replace('/', '\\', $path);
            $className = str_replace('.php', '', $className);

            $expl = explode('\\', $className);

            $namespace = $expl[1];
            $application = $expl[2];
            $bundle = $expl[3];
            $layer = count($expl) > 5 ? $expl[4] : false;

            if (false !== $layer) {
                $prefix = implode('\\', [$namespace, $application, $bundle, $layer]);
                $suffix = str_replace($prefix, '', $className);
                $suffix = str_replace('\\', '', $suffix);
                $map[$application . '|' . $bundle . '|' . $layer . '|' . $suffix] = $className;
            } else {
                $prefix = implode('\\', [$namespace, $application, $bundle]);
                $suffix = str_replace($prefix, '', $className);
                $suffix = str_replace('\\', '', $suffix);
                $map[$application . '|' . $bundle . '|' . $suffix] = $className;
            }
        }

        return $map;
    }

    /**
     * @return array|mixed
     */
    protected function createMap()
    {
        $basePaths = [
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/',
            APPLICATION_SOURCE_DIR,
        ];

        $map = [];

        foreach ($basePaths as $basePath) {
            $files = $this->createBasePath($basePath);
            $map = $this->buildMap($files, $map);
        }

        file_put_contents(APPLICATION_ROOT_DIR . '/.class_map', '<?php return ' . var_export($map, true) . ';');

        return $map;
    }

}
