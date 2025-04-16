<?php

namespace SprykerCi;

use Generated\Shared\Transfer\ModuleTransfer;

class CiCommand
{
    public function __construct(protected string $commandName, protected array $commandData, protected ModuleTransfer $moduleTransfer)
    {
    }

    public function getCommandName(): string
    {
        return $this->commandName;
    }

    public function getModuleName(): string
    {
        return sprintf('%s.%s', $this->moduleTransfer->getOrganization()->getName(), $this->moduleTransfer->getName());
    }

    public function shouldBeSkipped(): bool
    {
        return isset($this->commandData['organization']) && $this->commandData['organization'] !== $this->moduleTransfer->getOrganization()->getName();
    }

    public function hasFixCommand(): bool
    {
        return isset($this->commandData['fixCommand']);
    }

    public function getCommand(): array
    {
        return $this->resolveCommand($this->commandData['command']);
    }

    public function getFixCommand(): array
    {
        return $this->resolveCommand($this->commandData['fixCommand']);
    }

    public function getPath(): ?string
    {
        if (isset($this->commandData['requiresPath']) && $this->commandData['requiresPath']) {
            return $this->moduleTransfer->getPath();
        }

        return null;
    }

    public function isShellCommand(): bool
    {
        return isset($this->commandData['isShellCommand']) && $this->commandData['isShellCommand'] === true;
    }

    protected function resolveCommand(array $command): array
    {
        $moduleName = $this->getModuleNameForCommandRun();

        if ($this->commandData['hasModule'] === true) {
            if (isset($this->commandData['hasModuleInArgument']) && $this->commandData['hasModuleInArgument']) {

                $command[$this->commandData['moduleArgument']] = sprintf($command[$this->commandData['moduleArgument']], $moduleName);
            }

            if (!isset($this->commandData['hasModuleInArgument']) || !$this->commandData['hasModuleInArgument']) {
                $command[] = $moduleName;
            }
        }

        if (isset($this->commandData['hasPath']) && $this->commandData['hasPath'] === true) {
            $command[count($command) - 1] = sprintf($command[count($command) - 1], $this->moduleTransfer->getPath());
        }

        return $command;
    }

    protected function getModuleNameForCommandRun(): string
    {
        // When we need to run a command with the short module name, explode Namespace.ModuleName and use the short name
        if (isset($this->commandData['useFullName']) && $this->commandData['useFullName'] === false) {
            return $this->moduleTransfer->getName();
        }

        return $this->getModuleName();
    }
}
