<?php

require_once __DIR__ . '/../src/Lexer/TokenType.php';
require_once __DIR__ . '/../src/Lexer/Token.php';
require_once __DIR__ . '/../src/Lexer/Lexer.php';

require_once __DIR__ . '/../src/AST/Program.php';
require_once __DIR__ . '/../src/AST/FunctionDeclaration.php';
require_once __DIR__ . '/../src/AST/ReturnStatement.php';
require_once __DIR__ . '/../src/AST/IntegerLiteral.php';
require_once __DIR__ . '/../src/AST/OutputStatement.php';

require_once __DIR__ . '/../src/Parser/Parser.php';

require_once __DIR__ . '/../src/CodeGen/PhpGenerator.php';

use Compiler\Lexer\Lexer;
use Compiler\Parser\Parser;
use Compiler\CodeGen\PhpGenerator;

echo "Compiler startet..." . PHP_EOL;

$lexer = new Lexer();

echo "Lexer erstellt." . PHP_EOL;

$tokens = $lexer->getTokens();

echo "Tokens erhalten: " . count($tokens) . PHP_EOL;

$parser = new Parser($tokens);

echo "Parser erstellt." . PHP_EOL;

$program = $parser->parse();

echo "Parsing successful!" . PHP_EOL;

foreach ($program->functions as $function) {
    echo "Function: " . $function->name . PHP_EOL;
    echo "Return type: " . $function->returnType . PHP_EOL;

    foreach ($function->body as $statement) {
        echo "Statement: " . get_class($statement) . PHP_EOL;

        if ($statement instanceof \Compiler\AST\ReturnStatement) {
            echo "Return value: " . $statement->value->value . PHP_EOL;
        }
    }
}

$generator = new PhpGenerator();

$phpCode = $generator->generate($program);

echo PHP_EOL;
echo "Generated PHP:" . PHP_EOL;
echo "----------------" . PHP_EOL;
echo $phpCode;
echo "----------------" . PHP_EOL;

$buildDir = __DIR__ . '/../build';

if (!is_dir($buildDir)) {
    mkdir($buildDir, 0777, true);
}

$outputFile = $buildDir . '/output.php';

file_put_contents($outputFile, $phpCode);

echo PHP_EOL;
echo "Generated PHP written to: " . $outputFile . PHP_EOL;
?>