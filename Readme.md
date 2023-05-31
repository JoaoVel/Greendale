# Greendalelang
Greendalelang is a simple imperative language based on the show Community. It is supposed to be amusing to code in but at the same time make absolutely no sense in its syntax.

## State of the Language
At this point in time the language should support a simple hello world program. 

## Goals
The goal of the first half of the project is to implement an interpreter in python and extend the language to solve simple problems like the ones described in codechef or codewars.
The second half of the project is to implement a Bytecode interpreter in c for better performance.

## Task List
- [X] implement the lexer
- [ ] implement the parser
- [ ] implement the ast interpreter
- [ ] implement IO functionality
- [ ] implement variable assignment
- [ ] implement if else statement
- [ ] implement while loop
- [ ] implement function call

## Language description in BNF
```
<Program> ::= <IO> <expr> | <Function> <expr>
<expr> ::= "" | <IO> <expr> | <Function> <expr>
<Function> ::= "task" <FunctionName> "():" <Type> "{" <expr> <return>  "}"
<IO> ::= "quote" "(" <String> ")" ";"
<String> ::= "\"" <Char> "\""
<Char> ::= "" | <Num> <Char> | <Letter> <Char>
<return> ::= "result" <Num> ";" | "result" <String> ";"
<Type> ::= "Deangit"
```