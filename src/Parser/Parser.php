<?php

namespace Compiler\Parser;

use Compiler\Lexer\Token;
use Compiler\Lexer\TokenType;

use Compiler\AST\Program;
use Compiler\AST\FunctionDeclaration;
use Compiler\AST\ReturnStatement;
use Compiler\AST\IntegerLiteral;
use Compiler\AST\VariableDeclaration;
use Compiler\AST\OutputStatement;

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
        $returnType = $this->expect(TokenType::INT);
        $name = $this->expect(TokenType::IDENTIFIER);

        $this->expect(TokenType::LEFT_PAREN);
        $this->expect(TokenType::RIGHT_PAREN);

        $this->expect(TokenType::LEFT_BRACE);

        $body = [];

        while ($this->current()->type !== TokenType::RIGHT_BRACE) {
            $body[] = $this->parseStatement();
        }

        $this->expect(TokenType::RIGHT_BRACE);

        return new FunctionDeclaration(
            $returnType->value,
            $name->value,
            $body
        );
    }

    private function parseStatement()
    {
        switch ($this->current()->type) {

            case TokenType::INT:
                return $this->parseVariableDeclaration();

            case TokenType::COUT:
                return $this->parseOutputStatement();

            case TokenType::RETURN:
                return $this->parseReturnStatement();

            default:
                throw new \RuntimeException(
                    "Unknown statement: "
                    . $this->current()->type->name
                );
        }
    }

    private function parseVariableDeclaration(): VariableDeclaration
    {
        $type = $this->expect(TokenType::INT);
        $name = $this->expect(TokenType::IDENTIFIER);

        $this->expect(TokenType::EQUAL);

        $value = $this->expect(TokenType::INTEGER_LITERAL);

        $this->expect(TokenType::SEMICOLON);

        return new VariableDeclaration(
            $type->value,
            $name->value,
            new IntegerLiteral(
                (int) $value->value
            )
        );
    }

    private function parseOutputStatement(): OutputStatement
    {
        $this->expect(TokenType::COUT);

        $value = $this->current();

        if ($value->type === TokenType::IDENTIFIER) {
            $this->advance();

            $this->expect(TokenType::SEMICOLON);

            return new OutputStatement(
                "identifier",
                $value->value
            );
        }

        if ($value->type === TokenType::STRING_LITERAL) {
            $this->advance();

            $this->expect(TokenType::SEMICOLON);

            return new OutputStatement(
                "string",
                $value->value
            );
        }

        throw new \RuntimeException(
            "Expected identifier or string after std::cout, got "
            . $value->type->name
        );
    }

    private function parseReturnStatement(): ReturnStatement
    {
        $this->expect(TokenType::RETURN);

        $value = $this->expect(TokenType::INTEGER_LITERAL);

        $this->expect(TokenType::SEMICOLON);

        return new ReturnStatement(
            new IntegerLiteral(
                (int) $value->value
            )
        );
    }
}