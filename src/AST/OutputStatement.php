<?php

namespace Compiler\AST;

class OutputStatement{
    public string $type;
    public string $value;

    public function __construct(
        string $type,
        string $value
    ) {
        $this->type = $type;
        $this->value = $value;
    }
}
?>