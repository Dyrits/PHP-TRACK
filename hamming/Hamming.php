<?php

function distance($strandA, $strandB) {
    if (strlen($strandA) != strlen($strandB)) { throw new InvalidArgumentException("DNA strands must be of equal length."); }
    return count(array_diff_assoc(str_split($strandA), str_split($strandB)));
}
