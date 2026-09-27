<?php

namespace Compiler\Lexer;

use RuntimeException;

require_once __DIR__ . '/TokenType.php';
require_once __DIR__ . '/Token.php';

class Lexer
{
    private string $source;
    private int $position = 0;
    private array $tokens = [];

    public function __construct()
    {
        $source = file_get_contents(
            __DIR__ . "/../../examples/Integer.cpp"
        );

        if ($source === false) {
            throw new RuntimeException("Could not read source file");
        }

        $this->source = $source;

        $this->tokenize();
    }

    public function getTokens(): array
    {
        return $this->tokens;
    }

    private function tokenize(): void
    {
        while ($this->position < strlen($this->source)) {

            $char = $this->source[$this->position];

            if (ctype_space($char)) {
                $this->position++;
                continue;
            }

            if ($char === '{') {
                $this->tokens[] = new Token(
                    TokenType::LEFT_BRACE,
                    "{"
                );

                $this->position++;
                continue;
            }

            if ($char === '}') {
                $this->tokens[] = new Token(
                    TokenType::RIGHT_BRACE,
                    "}"
                );

                $this->position++;
                continue;
            }

            if ($char === '(') {
                $this->tokens[] = new Token(
                    TokenType::LEFT_PAREN,
                    "("
                );

                $this->position++;
                continue;
            }

            if ($char === ')') {
                $this->tokens[] = new Token(
                    TokenType::RIGHT_PAREN,
                    ")"
                );

                $this->position++;
                continue;
            }

            if ($char === ';') {
                $this->tokens[] = new Token(
                    TokenType::SEMICOLON,
                    ";"
                );

                $this->position++;
                continue;
            }

            if (ctype_digit($char)) {
                $value = "";

                while (
                    $this->position < strlen($this->source)
                    && ctype_digit($this->source[$this->position])
                ) {
                    $value .= $this->source[$this->position];
                    $this->position++;
                }

                $this->tokens[] = new Token(
                    TokenType::INTEGER_LITERAL,
                    $value
                );

                continue;
            }

            if ($char === '"') {
                $this->tokens[] = new Token(
                    TokenType::STRING_LITERAL,
                    $this->lexString()
                );

                continue;
            }

            if (ctype_alpha($char) || $char === '_') {
                $word = $this->lexChar();

                if ($word === "int") {
                    $this->tokens[] = new Token(
                        TokenType::INT,
                        $word
                    );
                }
                elseif ($word === "return") {
                    $this->tokens[] = new Token(
                        TokenType::RETURN,
                        $word
                    );
                }
                else {
                    $this->tokens[] = new Token(
                        TokenType::IDENTIFIER,
                        $word
                    );
                }

                continue;
            }

            throw new RuntimeException(
                "Unknown character: " . $char
            );
        }

        $this->tokens[] = new Token(
            TokenType::EOF,
            ""
        );
    }

    private function lexString(): string
    {
        $value = "";

        // Öffnendes " überspringen
        $this->position++;

        while ($this->position < strlen($this->source)) {

            $char = $this->source[$this->position];

            if ($char === '"') {
                $this->position++;

                return $value;
            }

            $value .= $char;
            $this->position++;
        }

        throw new RuntimeException('Missing closing "');
    }

    private function lexChar(): string
    {
        $value = "";

        while (
            $this->position < strlen($this->source)
            && (
                ctype_alnum($this->source[$this->position])
                || $this->source[$this->position] === '_'
            )
        ) {
            $value .= $this->source[$this->position];
            $this->position++;
        }

        return $value;
    }
}