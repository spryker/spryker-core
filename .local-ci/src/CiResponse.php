<?php

namespace SprykerCi;

class CiResponse
{
    protected array $errors = [];
    protected array $skippedModuleCommands = [];

    public function addError(string $moduleName, string $commandName, string $command): self
    {
        $this->errors[$moduleName][$commandName] = $command;

        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
