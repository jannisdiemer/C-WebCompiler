<?php 

namespace Compiler\Lexer;

enum TokenType
{
    case INT;
    case STRING;
    case FLOAT;
    case IDENTIFIER;

    case LEFT_PAREN;
    case RIGHT_PAREN;
    
    case LEFT_BRACE;
    case RIGHT_BRACE;

    case RETURN;

    case INTEGER_LITERAL;
    case FLOAT_LITERAL;
    case STRING_LITERAL;
    case SEMICOLON;

    case EOF;

    case EQUAL;
}

?>