<?php

namespace Compiler\Parser;

use Compiler\Lexer\Token;
use Compiler\Lexer\TokenType;

use Compiler\AST\Program;
use Compiler\AST\FunctionDeclaration;
use Compiler\AST\ReturnStatement;
use Compiler\AST\IntegerLiteral;

class Parser
{
    private array $tokens;
    private int $position = 0;

    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    private function current(): Token
    {
        return $this->tokens[$this->position];
    }

    private function advance(): Token
    {
        return $this->tokens[$this->position++];
    }

    private function expect(TokenType $type): Token
    {
        $token = $this->current();

        if ($token->type !== $type) {
            throw new \RuntimeException(
                "Expected {$type->name}, got {$token->type->name}"
            );
        }

        return $this->advance();
    }

    public function parse(): Program
    {
        $program = new Program();

        while ($this->current()->type !== TokenType::EOF) {
            $program->addFunction(
                $this->parseFunction()
            );
        }

        return $program;
    }

    private function parseFunction(): FunctionDeclaration
    {
        // int
        $returnType = $this->expect(TokenType::INT);

        // main
        $name = $this->expect(TokenType::IDENTIFIER);

        // (
        $this->expect(TokenType::LEFT_PAREN);

        // )
        $this->expect(TokenType::RIGHT_PAREN);

        // {
        $this->expect(TokenType::LEFT_BRACE);

        $body = [];

        // return 5;
        $body[] = $this->parseReturnStatement();

        // }
        $this->expect(TokenType::RIGHT_BRACE);

        return new FunctionDeclaration(
            $returnType->value,
            $name->value,
            $body
        );
    }

    private function parseReturnStatement(): ReturnStatement
    {
        // return
        $this->expect(TokenType::RETURN);

        // 5
        $value = $this->expect(TokenType::INTEGER_LITERAL);

        // ;
        $this->expect(TokenType::SEMICOLON);

        return new ReturnStatement(
            new IntegerLiteral(
                (int) $value->value
            )
        );
    }
}