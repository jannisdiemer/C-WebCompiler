<?php

namespace Compiler\AST;

class Program{
    public array $functions = [];

    public function addFunction(FunctionDeclaration $function): void {
        $this->functions[] = $function;
    }
}
?>