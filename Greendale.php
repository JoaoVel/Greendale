<?php
//Constants definitions
define("TOKEN_NAME",   "TOKEN_NAME");
define("TOKEN_DIGIT", "TOKEN_DIGIT");
define("TOKEN_TASK",   "TOKEN_TASK");
define("TOKEN_RESULT", "TOKEN_RESULT");
define("TOKEN_TASKCALL", "TOKEN_TASKCALL");
define("TOKEN_QMARK", "TOKEN_QMARK");
define("TOKEN_OPAREN", "TOKEN_OPAREN");
define("TOKEN_CPAREN", "TOKEN_CPAREN");
define("TOKEN_OCURLY", "TOKEN_OCURLY");
define("TOKEN_CCURLY", "TOKEN_CCURLY");
define("TOKEN_SCOLEN", "TOKEN_SCOLEN");
define("TOKEN_COLEN",  "TOKEN_COLEN");


Class Token {
    public $type;
    public $value;
    public $start;
    public $line;

    public function __construct($type, $value, $start, $line) {
        $this->type = $type;
        $this->value = $value;
        $this->start = $start;
        $this->line = $line;
    }
}

class Node {
    public $type;
    public $name;
    public $value;
    public $children;

    public function __construct($type, $name="", $value=null, $children=[]) {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->children = $children;
    }
}

class Lexer {
    private $source;
    private $cur;
    private $bol;
    private $row;
    public $tokens;

    // This is the constructor for the lexer
    // it receives a string and initializes the variables
    public function __construct($source) {
        $this->source = $source;
        $this->cur = 0;
        $this->bol = 0;
        $this->row = 1;
        $this->tokens = array();
    }

    // This is the function that check if we reached the end of the file
    private function is_empty() {
        return $this->cur >= strlen($this->source);
    }

    private function parse_word() {
        $start = $this->cur - $this->bol;
        $word = "";
        while (ctype_alnum($this->source[$this->cur]) || $this->source[$this->cur] === "_") {
            $word = $word . $this->source[$this->cur];
            $this->cur += 1;
        }

        if ($word === "task") {
            return new Token (TOKEN_TASK, TOKEN_TASK, $start, $this->row);
        } else if ($word === "outcome") {
            return new Token (TOKEN_RESULT, TOKEN_RESULT, $start, $this->row);
        } else if ($word === "quote") {
            return new Token (TOKEN_TASKCALL, $word, $start, $this->row);
        } else if ($word === "Deangit") {
            return new Token (TOKEN_DIGIT, TOKEN_DIGIT, $start, $this->row);
        } 
        return new Token(TOKEN_NAME, $word, $start, $this->row);
    }

    private function parse_number(){
        $start = $this->cur - $this->bol;
        $number = "";

        while (ctype_digit($this->source[$this->cur])) {
            $number = $number . $this->source[$this->cur];
            $this->cur += 1;
        }

        return new Token(TOKEN_DIGIT, intval($number), $start, $this->row);
    }

    // This function returns the next token it finds
    public function next_token(){
        // Checks for end of file
        if ($this->is_empty()) {
            return 0;
        }

        // gets the next letter
        $letter = $this->source[$this->cur];

        // Checks for whitespace
        if (ctype_space($letter) && $this->source[$this->cur] !== "\n"){
            $this->cur += 1;
            return $this->next_token();
        } else if ($letter === "\n") {
            $this->cur += 1;
            $this->bol = $this->cur;
            $this->row += 1;
            return $this->next_token();
        }

        // checks for keywords
        if (ctype_alpha($letter)) {
            //Calls function that returns the selected token
            array_push($this->tokens,$this->parse_word());
            return $this->next_token();
        } else if (ctype_digit($letter)) {
             array_push($this->tokens,$this->parse_number());
             return $this->next_token();
        } else if ($letter === '(') {
            $token = new Token(TOKEN_OPAREN, $letter, $this->cur, $this->row);
            array_push($this->tokens,$token);
        } else if ($letter === ')') {
            $token = new Token(TOKEN_CPAREN, $letter, $this->cur, $this->row);
            array_push($this->tokens,$token);
        } else if ($letter === ':') {
            $token = new Token(TOKEN_COLEN, $letter, $this->cur, $this->row);
            array_push($this->tokens,$token);
        } else if ($letter === "\"") {
            $token = new Token(TOKEN_QMARK, $letter, $this->cur, $this->row);
            array_push($this->tokens,$token);
        } else if ($letter === ";") {
            $token = new Token(TOKEN_SCOLEN, $letter, $this->cur, $this->row);
            array_push($this->tokens,$token);
        }

        // increments the cursor position and calls the function again
        $this->cur += 1;
        return $this->next_token();
    }
}

// This is th parser class
// it generates an ast of the lexed code
class Parser {
    private $lexer;
    private $pos;
    private $ast;

    public function __construct($lexer) {
        $this->lexer = $lexer;
        $this->pos = 0;
        $this->ast = new AST();
    }

    private function parse_function() {
        $node = new Node();
    }

    public function parse(){
        foreach($this->lexer->tokens as $token){
            if ($token->type === TOKEN_TASK) {
                // define parse function
                $this->parse_function();
            } else if ($token->type === TOKEN_TASKCALL) {
                // define parse function call
            }

        }
    }
}

// Check for input file
if ($argc < 2) {
    echo "No input file specified\n";
    exit(-1);
}

$filename = $argv[1];
$source = file_get_contents($filename);
if (!$source) exit(-1);
$lexer = new Lexer($source);
$lexer->next_token();
$parser = new Parser($lexer);
$parser->parse();