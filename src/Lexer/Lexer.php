<?php
namespace Compiler\Lexer;

use RuntimeException;

require_once __DIR__ . '/TokenType.php';
require_once __DIR__ . '/Token.php';

$source = file_get_contents(__DIR__ . "/../../examples/Integer.cpp");

$position = 0;

$tokens = [];

if ($source === false) {
    throw new RuntimeException("Could not read source file");
}

function lex_string(String $source, Int &$position) {
    $value = "";

    $position++;

    while($position < strlen($source)) {
        $char = $source[$position];

        if($char == '"') {
            $position++;
            return $value;
        }

        $value .= $char;
        $position++;;
    }

    throw new RuntimeException('Missing closing "');
    
}

function lex_char(String $source, Int &$position) {
    $value = "";

    while($position < strlen($source) && (ctype_alnum($source[$position]) || $source[$position] === '_')) {
        $char = $source[$position];
        $value .= $char;
        $position++;;
    }

    return $value;
}

while ($position < strlen($source)) {
    $char = $source[$position];

    if(ctype_space($char)) {
        $position++;
        continue;
    }

    if($char === '{') {
        $tokens[] = new Token(TokenType::LEFT_BRACE, "{");
        $position++;
        continue;
    }

    if($char === '}') {
        $tokens[] = new Token(TokenType::RIGHT_BRACE, "}");
        $position++;
        continue;
    }

    if($char === '(') {
        $tokens[] = new Token(TokenType::LEFT_PAREN, "(");
        $position++;
        continue;
    }

    if($char === ')') {
        $tokens[] = new Token(TokenType::RIGHT_PAREN, ")");
        $position++;
        continue;
    }

    if($char === ';') {
        $tokens[] = new Token(TokenType::SEMICOLON, ";");
        $position++;
        continue;
    }

    if(ctype_digit($char)) {
        $tokens[] = new Token(TokenType::INTEGER_LITERAL, $char);
        $position++;
        continue;
    }

    if($char === '"') {
        $tokens[] = new Token(TokenType::STRING_LITERAL, lex_string($source, $position));
        continue;
    }

    if(ctype_alpha($char) || $char === '_') {
        $word =  lex_char($source, $position); 

        if($word === "int") {
            $tokens[] = new Token(TokenType::INT, $word); 
        }
        elseif($word === "return") {
            $tokens[] = new Token(TokenType::RETURN, $word); 
        }
        else {
            $tokens[] = new Token(TokenType::IDENTIFIER, $word); 
        }
        continue;
    }

    throw new RuntimeException("Unknown character" . $char);
}

$tokens[] = new Token(TokenType::EOF, "");

foreach ($tokens as $token) {
    echo $token->type->name . " : " . $token->value . PHP_EOL;
}

?>