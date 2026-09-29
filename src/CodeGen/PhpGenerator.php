<?php

namespace Compiler\CodeGen;

use Compiler\AST\Program;
use Compiler\AST\ReturnStatement;
use Compiler\AST\OutputStatement;

class PhpGenerator
{
    public function generate(Program $program): string
    {
        $output = "<?php\n\n";

        foreach ($program->functions as $function) {

            $output .= "function " . $function->name . "() {\n";

            foreach ($function->body as $statement) {

                if ($statement instanceof OutputStatement) {

                    if ($statement->type === "string") {
                        $output .= "    echo \"" . $statement->value . "\";\n";
                    }

                }

                if ($statement instanceof ReturnStatement) {
                    $output .= "    return "
                        . $statement->value->value
                        . ";\n";
                }
            }

            $output .= "}\n\n";
        }

        $output .= "?>";

        return $output;
    }
}