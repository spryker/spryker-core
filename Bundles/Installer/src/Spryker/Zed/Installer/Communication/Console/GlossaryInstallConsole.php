<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Spryker\Zed\Installer\Business\InstallerFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method InstallerFacade getFacade()
 */
class GlossaryInstallConsole extends Console
{

    const COMMAND_NAME = 'setup:install-glossary';
    const DESCRIPTION = 'Install Glossary';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription(self::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Spryker\Zed\Installer\Business\Model\GlossaryInstaller $glossaryInstaller */
        $glossaryInstaller = $this->getFacade()->getGlossaryInstaller();

        $messenger = $this->getMessenger();

        $glossaryInstaller->setMessenger($messenger);
        $result = $glossaryInstaller->install();

        $this->info($this->buildSummary($result));
    }

    /**
     * @param array $results
     *
     * @return string
     */
    private function buildSummary(array $results)
    {
        $summary = 'Export Glossary Keys finished:' . PHP_EOL . PHP_EOL;

        foreach ($results as $file => $result) {
            $summary .= sprintf(
                '<fg=yellow>Export file</fg=yellow> %s:' . PHP_EOL,
                str_replace(APPLICATION_ROOT_DIR, '', $file)
            );

            foreach ($result as $glossaryKey => $data) {
                $summary .= sprintf(
                    '%s<fg=blue>%s:</fg=blue> %s' . PHP_EOL,
                    $data['created'] ? '* ' : '',
                    $glossaryKey,
                    !$data['created'] ? '<fg=white>already in db</fg=white>' : 'key created'
                );

                foreach ($data['translation'] as $language => $content) {
                    if ($content['created'] || $content['updated']) {
                        $summary .= sprintf(
                            ' <fg=white>%s:</fg=white> <fg=magenta>%s</fg=magenta> %s' . PHP_EOL,
                            $language,
                            $content['text'],
                            !$content['updated'] ? '' : '(translation has changed)'
                        );
                    }
                }
            }
        }

        return $summary;
    }

}
