<?php

require_once __DIR__ . '/../src/Lexer/TokenType.php';
require_once __DIR__ . '/../src/Lexer/Token.php';
require_once __DIR__ . '/../src/Lexer/Lexer.php';

require_once __DIR__ . '/../src/AST/Program.php';
require_once __DIR__ . '/../src/AST/FunctionDeclaration.php';
require_once __DIR__ . '/../src/AST/ReturnStatement.php';
require_once __DIR__ . '/../src/AST/IntegerLiteral.php';

require_once __DIR__ . '/../src/Parser/Parser.php';

use Compiler\Lexer\Lexer;
use Compiler\Parser\Parser;

echo "Compiler startet..." . PHP_EOL;

$lexer = new Lexer();

echo "Lexer erstellt." . PHP_EOL;

$tokens = $lexer->getTokens();

echo "Tokens erhalten: " . count($tokens) . PHP_EOL;

$parser = new Parser($tokens);

echo "Parser erstellt." . PHP_EOL;

$program = $parser->parse();

echo "Parsing successful!" . PHP_EOL;
?>