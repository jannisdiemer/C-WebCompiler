<?php

namespace Compiler\AST;

class IntegerLiteral {
    public int $value;

    public function __construct(int $value) {
        $this->value = $value;
    }
}
?>