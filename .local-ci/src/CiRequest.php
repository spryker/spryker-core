<?php

namespace SprykerCi;

use Generated\Shared\Transfer\ModuleTransfer;

class CiRequest
{
    /**
     * @var array<string, ModuleTransfer>
     */
    protected array $modulesToRun = [];
    protected array $modulesToSkip = [];
    protected array $modulesUsedByChangedModule = [];
    protected array $commandsToRun = [];
    protected array $commandsToSkip = [];

    protected bool $shouldRunPreCommands = false;
    protected bool $shouldRunFixCommands = false;

    public function addModuleToRun(string $moduleToRun, ModuleTransfer $moduleTransfer): self
    {
        $this->modulesToRun[$moduleToRun] = $moduleTransfer;

        return $this;
    }

    public function addModuleToSkip(string $moduleToRun): self
    {
        $this->modulesToSkip[] = $moduleToRun;

        return $this;
    }

    public function getModulesToRun(): array
    {
        return $this->modulesToRun;
    }

    public function getModulesToSkip(): array
    {
        return $this->modulesToSkip;
    }

    public function addModuleUsedByChangedModule(string $moduleName, string $usedByModuleName): self
    {
        $this->modulesUsedByChangedModule[$moduleName][] = $usedByModuleName;

        return $this;
    }

    public function getModulesUsedByChangedModules(): array
    {
        return $this->modulesUsedByChangedModule;
    }

    public function addCommandToRun(string $commandToRun, array $commandDefinition): self
    {
        $this->commandsToRun[$commandToRun] = $commandDefinition;

        return $this;
    }

    public function addCommandToSkip(string $commandToRun): self
    {
        $this->commandsToSkip[] = $commandToRun;

        return $this;
    }

    public function getCommandsToRun(): array
    {
        return $this->commandsToRun;
    }

    public function getCommandsToSkip(): array
    {
        return $this->commandsToSkip;
    }

    public function setShouldRunPreCommands(bool $shouldRunPreCommands): self
    {
        $this->shouldRunPreCommands = $shouldRunPreCommands;

        return $this;
    }
    public function shouldRunPreCommands(): bool
    {
        return $this->shouldRunPreCommands;
    }

    public function setShouldRunFixCommands(bool $shouldRunFixCommands): self
    {
        $this->shouldRunFixCommands = $shouldRunFixCommands;

        return $this;
    }

    public function shouldRunFixCommands(): bool
    {
        return $this->shouldRunFixCommands;
    }
}

