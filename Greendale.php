<?php
//Constants definitions
define("TOKEN_NAME",   "TOKEN_NAME");
define("TOKEN_TASK",   "TOKEN_TASK");
define("TOKEN_RESULT", "TOKEN_RESULT");
define("TOKEN_QUOTE", "TOKEN_QUOTE");
define("TOKEN_OPAREN", "TOKEN_OPAREN");
define("TOKEN_CPAREN", "TOKEN_CPAREN");
define("TOKEN_OCURLY", "TOKEN_OCURLY");
define("TOKEN_CCURLY", "TOKEN_CCURLY");
define("TOKEN_SCOLEN", "TOKEN_SCOLEN");
define("TOKEN_COLEN",  "TOKEN_COLEN");


Class Token {
    public $type;
    public $value;

    public function __construct($type, $value) {
        $this->type = $type;
        $this->value = $value;
    }
}

class Lexer {
    private $source;
    private $cur;
    private $bol;
    private $row;
    private $tokens;

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
        $start = $this->cur;
        $word = "";
        while (ctype_alnum($this->source[$this->cur]) || $this->source[$this->cur] === "_") {
            $word = $word . $this->source[$this->cur];
            $this->cur += 1;
        }
        
        print($word . "\n");

        if ($word === "task") {
            return TOKEN_TASK;
        } else if ($word === "result") {
            return TOKEN_RESULT;
        } else if ($word === "quote") {
            return TOKEN_QUOTE;
        } 
        return new Token(TOKEN_NAME, $word);
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
        }

        // increments the cursor position and calls the function again
        $this->cur += 1;
        return $this->next_token();
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