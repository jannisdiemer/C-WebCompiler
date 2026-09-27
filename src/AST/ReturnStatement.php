<?php

namespace Compiler\AST;

class ReturnStatement{
    public IntegerLiteral $value;

    public function __construct(IntegerLiteral $value) {
        $this->value = $value;
    }
}
?>