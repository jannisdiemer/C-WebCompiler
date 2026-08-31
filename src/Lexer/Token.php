<?php 
namespace Compiler\Lexer;

use Compiler\Lexer\TokenType;

class Token 
{
    public TokenType $type;
    public string $value;

    public function __construct(TokenType $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}
?>