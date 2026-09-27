<?php

namespace Compiler\AST;

class FunctionDeclaration {
    public string $returnType;
    public string $name;
    public array $body;

    public function __construct(string $returnType, string $name, array $body) {
        $this->returnType = $returnType;
        $this->name = $name;
        $this->body = $body;
    }
}
?>
