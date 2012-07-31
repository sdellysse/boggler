<?php
if (!class_exists("TrieNode")) {
    class TrieNode
    {
        protected $word;
        protected $nodes = array();

        public function addWord($word, $offset)
        {
            if ($word === null) {
                $word = "";
            }

            if ($offset === null) {
                $offset = 0;
            }

            if ($offset === (strlen($word))) {
                $this->word = $word;
            } else {
                $node = $this->nodes[$word[$offset]] ?: new static;
                $node->addWord($word, $offset + 1);
                $this->nodes[$word[$offset]] = $node;
            }
        }
        public function isWord()
        {
            return !!$this->word;
        }

        public function getNode($letter)
        {
            return $this->nodes[$letter];
        }

        public function getWord()
        {
            return $this->word;
        }
    }
}
