<?php

/**
 * ./1/BinomialHeap.php
 * 231232028 - Falmesino Abdul Hamid
 */

class BinomialHeapNode {
  public $value;
  public $degree;
  public $parent;
  public $child;
  public $sibling;

  public function __construct($value) {
    $this->value = $value;
    $this->degree = 0;
    $this->parent = null;
    $this->child = null;
    $this->sibling = null;
  }
}

class BinomialHeap {
  private $head;

  public function __construct() {
    $this->head = null;
  }

  private function link(BinomialHeapNode $y, BinomialHeapNode $z) {
    $y->parent = $z;
    $y->sibling = $z->child;
    $z->child = $y;
    $z->degree++;
  }

  private function merge(?BinomialHeapNode $h1, ?BinomialHeapNode $h2): ?BinomialHeapNode {
    // If one of the heaps is empty, return the other
    if ($h1 === null) {
      return $h2;
    }
    if ($h2 === null) {
      return $h1;
    }
  
    $head = new BinomialHeapNode(null);
    $tail = $head;
  
    while ($h1 !== null && $h2 !== null) {
      if ($h1->degree <= $h2->degree) {
        $tail->sibling = $h1;
        $h1 = $h1->sibling;
      } else {
        $tail->sibling = $h2;
        $h2 = $h2->sibling;
      }
      $tail = $tail->sibling;
    }
  
    if ($h1 !== null) {
      $tail->sibling = $h1;
    } else {
      $tail->sibling = $h2;
    }
  
    return $head->sibling;
  }

  public function union(BinomialHeap $h) {
    $this->head = $this->merge($this->head, $h->head);
    if ($this->head === null) {
      return;
    }

    $prev = null;
    $curr = $this->head;
    $next = $curr->sibling;

    while ($next !== null) {
      if ($curr->degree != $next->degree || ($next->sibling !== null && $next->sibling->degree == $curr->degree)) {
        $prev = $curr;
        $curr = $next;
      } elseif ($curr->value <= $next->value) {
        $curr->sibling = $next->sibling;
        $this->link($next, $curr);
      } else {
        if ($prev === null) {
          $this->head = $next;
        } else {
          $this->sibling = $next;
        }
        $this->link($curr, $next);
        $curr = $next;
      }
      $next = $curr->sibling;
    }
  }

  public function insert($value) {
    $temp = new BinomialHeap();
    $temp->head = new BinomialHeapNode($value);
    $this->union($temp);
  }

  public function extractMin() {
    if ($this->head === null) {
      throw new Exception("Heap is empty");
    }

    $min = $this->head;
    $minPrev = null;
    $curr = $this->head;
    $prev = null;

    while ($curr !== null) {
      if ($curr->value < $min->value) {
        $min = $curr;
        $minPrev = $prev;
      }
      $prev = $curr;
      $curr = $curr->sibling;
    }

    if ($minPrev === null) {
      $this->head = $min->sibling;
    } else {
      $minPrev->sibling = $min->sibling;
    }

    $child = $min->child;
    $tempHead = null;

    while ($child !== null) {
      $nextChild = $child->sibling;
      $child->sibling = $tempHead;
      $child->parent = null;
      $tempHead = $child;
      $child = $nextChild;
    }

    $tempHeap = new BinomialHeap();
    $tempHeap->head = $tempHead;
    $this->union($tempHeap);

    return $min->value;
  }
}

// Test-case
$binomialHeap = new BinomialHeap();

// Test 1: Insert values and extract the minimum
$binomialHeap->insert(5);
$binomialHeap->insert(3);
$binomialHeap->insert(7);
echo $binomialHeap->extractMin() . "\n"; // Expected: 3

// Test 2: Insert more values and extract the minimum
$binomialHeap->insert(2);
$binomialHeap->insert(8);
echo $binomialHeap->extractMin() . "\n"; // Expected: 2

// Test 3: Extract from an empty heap (should throw an exception)
try {
    $emptyHeap = new BinomialHeap();
    $emptyHeap->extractMin();
} catch (Exception $e) {
    echo "Caught exception: " . $e->getMessage() . "\n"; // Expected: Heap is empty
}

// Test 4: Union of two heaps
$heap1 = new BinomialHeap();
$heap1->insert(1);
$heap1->insert(4);

$heap2 = new BinomialHeap();
$heap2->insert(2);
$heap2->insert(3);

$heap1->union($heap2);
echo $heap1->extractMin() . "\n"; // Expected: 1
echo $heap1->extractMin() . "\n"; // Expected: 2

?>