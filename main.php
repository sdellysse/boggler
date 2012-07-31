<?php
error_reporting(E_ERROR);

include __DIR__."/include/array_flatten.php";
include __DIR__."/include/TrieNode.php";

# Finds all possible words from a given space, making sure not to backtrack
#
# grid: input array
# parentTrie: either root node or current node of the caller
# {row,column}Index: currentSpace location
# visited: a chain of already visited spaces
function findWords($grid, $parentTrie, $rowIndex, $columnIndex, $visited = array())
{
    $retval = array();

    # Only process if there are possibilities.
    if ($trieNode = $parentTrie->getNode($grid[$rowIndex][$columnIndex])) {
        # Add to chain
        $visited []= array(
            "rowIndex" => $rowIndex,
            "columnIndex" => $columnIndex,
        );

        # Keep track of complete words
        if ($trieNode->isWord()) {
            $retval []= $trieNode->getWord();
        }

        # Visit each neighbor
        foreach (array(-1, 0, 1) as $rowModifier) {
            foreach (array(-1, 0, 1) as $columnModifier) {
                $r = $rowIndex + $rowModifier;
                $c = $columnIndex + $columnModifier;
                $hasBeenVisited = in_array(array("rowIndex" => $r, "columnIndex" => $c), $visited);
                if (!$hasBeenVisited) {
                    $retval = array_merge($retval, findWords($grid, $trieNode, $r, $c, $visited));
                }
            }
        }
    }

    return $retval;
}

function boggleSolve($grid, $wordlist)
{
    # Eliminate words that contain any characters that are not in the grid
    $letters = array_unique(array_flatten($grid));
    $words = array_filter($wordlist, function ($word) use ($letters) {
        foreach (str_split($word) as $letter) {
            if (!in_array($letter, $letters)) {
                return false;
            }
        }
        return true;
    });

    # Stuff all the words into the trie
    $trie = new TrieNode;
    foreach ($words as $word) {
        $trie->addWord($word);
    }

    # Find all possible words for each space
    $foundWords = array();
    foreach ($grid as $rowIndex => $row) {
        foreach($row as $columnIndex => $column) {
            $foundWords = array_merge(
                $foundWords,
                findWords($grid, $trie, $rowIndex, $columnIndex)
            );
        }
    }

    return array_values(array_unique($foundWords));
}

# Default Main
if (!debug_backtrace()) {
    $grid = array(
        array('s', 't', 'a', 'e'),
        array('d', 'r', 'p', 's'),
        array('e', 'i', 'l', 'o'),
        array('s', 'd', 'n', 'r'),
    );
    $wordlist = array_filter(
        array_map("trim",
            explode("\n", file_get_contents(__DIR__."/words.txt"))
        )
    );

    echo implode("\n", boggleSolve($grid, $wordlist))."\n";
}
