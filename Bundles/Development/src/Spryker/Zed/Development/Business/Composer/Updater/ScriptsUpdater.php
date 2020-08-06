<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use stdClass;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Parser;

class ScriptsUpdater implements UpdaterInterface
{
    public const KEY_SCRIPTS = 'scripts';

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile): array
    {
        $path = pathinfo($composerJsonFile, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR;
        $composerJson = $this->assertCsScripts($path, $composerJson);

        if (isset($composerJson['scripts']) && empty($composerJson['scripts'])) {
            $composerJson['scripts'] = new stdClass();
        }

        return $composerJson;
    }

    /**
     * @param string $path
     * @param array $jsonArray
     *
     * @return array
     */
    protected function assertCsScripts(string $path, array $jsonArray): array
    {
        $requiresCodeSniffer = is_dir($path . 'src');
        if (!$requiresCodeSniffer) {
            unset($jsonArray['scripts']['cs-check']);
            unset($jsonArray['scripts']['cs-fix']);

            return $jsonArray;
        }

        $standard = $this->extractStandard($path);
        $folders = [
            'src/',
        ];
        if (is_dir($path . 'tests')) {
            $folders[] = 'tests/';
        }

        $currentCsCheck = $jsonArray['scripts']['cs-check'] ?? '';
        $ignores = '';
        preg_match('/--ignore=[^ ]+/', $currentCsCheck, $matches);
        if ($matches) {
            $ignores = $matches[0] . ' ';
        }

        $standardPath = '--standard=vendor/spryker/code-sniffer/' . $standard . '/ruleset.xml ';

        $newCsCheck = 'phpcs -p -s ' . $standardPath . $ignores . implode(' ', $folders);
        $newCsFix = 'phpcbf -p ' . $standardPath . $ignores . implode(' ', $folders);

        $jsonArray['scripts']['cs-check'] = $newCsCheck;
        $jsonArray['scripts']['cs-fix'] = $newCsFix;

        return $jsonArray;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function extractStandard(string $path): string
    {
        $standard = 'Spryker';
        if (file_exists($path . 'tooling.yml')) {
            $yamlParser = new Parser();
            $config = $yamlParser->parseFile($path . 'tooling.yml');
            if (!empty($config['code-sniffer']['level']) && $config['code-sniffer']['level'] === 2) {
                return 'SprykerStrict';
            }
        }

        return $standard;
    }
}
