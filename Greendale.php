<?php

// Classes needed to parse the file
class Lexer {
    private $source;
    private $cur;
    private $bol;

    public function __construct($source) {
        $this->source = $source;
        $this->cur = 0;
        $this->bol = 0;
        $this->row = 1;
    }

    public function is_empty() {
        return $this->cur >= strlen($this->source);
    }

    public function next_token(){
        if ($this->is_empty()) {
            return 0;
        }

        $letter = $this->source[$this->cur];

        if ($letter === " ") {
            $this->cur += 1;
        } else if ($letter === "\n") {
            $this->cur += 1;
            $this->bol = $this->cur;
            $this->row += 1;
        }

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