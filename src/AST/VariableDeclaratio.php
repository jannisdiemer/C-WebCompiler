<?php

namespace Compiler\AST;

class VariableDeclaration{
    public string $type;
    public string $name;
    public IntegerLiteral $value;

    public function __construct(
        string $type,
        string $name,
        IntegerLiteral $value
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
    }
}
?>