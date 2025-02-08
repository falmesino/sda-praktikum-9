<?php

/**
 * ./1/FibonacciHeap.php
 * 231232028 - Falmesino Abdul Hamid
 */

class FibonacciHeapNode {
  public $value;
  public $degree;
  public $marked;
  public $parent;
  public $child;
  public $left;
  public $right;

  public function __construct($value) {
    $this->value = $value;
    $this->degree = 0;
    $this->marked = false;
    $this->parent = null;
    $this->child = null;
    $this->left = $this;
    $this->right = $this;
  }
}

class FibonacciHeap {
  private $minNode;
  private $totalNodes;

  public function __construct() {
    $this->minNode = null;
    $this->totalNodes = 0;
  }

  private function merge($a, $b) {
    if ($a === null) return $b;
    if ($b === null) return $a;

    if ($a->value > $b->value) {
      $temp = $a;
      $a = $b;
      $b = $temp;
    }

    $aRight = $a->right;
    $bLeft = $b->left;

    $a->right = $b;
    $b->left = $a;
    $aRight->left = $bLeft;
    $bLeft->right = $aRight;

    return $a;
  }

  public function insert($value) {
    $node = new FibonacciHeapNode($value);
    if ($this->minNode !== null) {
      $this->minNode = $this->merge($this->minNode, $node);
    } else {
      $this->minNode = $node;
    }
    $this->totalNodes++;
  }

  public function extractMin() {
    $min = $this->minNode;
    if ($min !== null) {
      if ($min->child !== null) {
        $child = $min->child;
        do {
          $next = $child->right;
          $child->parent = null;
          $this->minNode = $this->merge($this->minNode, $child);
          $child = $next;
        } while ($child !== $min->child);
      }

      $min->left->right = $min->right;
      $min->right->left = $min->left;

      if ($min === $min->right) {
        $this->minNode = null;
      } else {
        $this->minNode = $min->right;
        $this->consolidate();
      }
      $this->totalNodes--;
    }
    return $min ? $min->value : null;
  }

  private function consolidate() {
    $array = array_fill(0, floor(log($this->totalNodes) / log(2)) + 1, null);
    $nodes = array();
    $current = $this->minNode;
  
    // Collect all root nodes
    if ($current !== null) {
      do {
        $nodes[] = $current;
        $current = $current->right;
      } while ($current !== $this->minNode);
    }
  
    // Consolidate nodes with the same degree
    foreach ($nodes as $node) {
      $degree = $node->degree;
      while ($array[$degree] !== null) {
        $other = $array[$degree];
        if ($node->value > $other->value) {
          // Swap nodes to ensure $node has the smaller value
          $temp = $node;
          $node = $other;
          $other = $temp;
        }
        $this->link($other, $node); // Link $other under $node
        $array[$degree] = null;
        $degree++;
      }
      $array[$degree] = $node;
    }
  
    // Rebuild the root list from the array
    $this->minNode = null;
    foreach ($array as $node) {
      if ($node !== null) {
        if ($this->minNode === null) {
          // Initialize the root list with the first node
          $this->minNode = $node;
          $this->minNode->left = $this->minNode;
          $this->minNode->right = $this->minNode;
        } else {
          // Merge the node into the root list
          $this->minNode = $this->merge($this->minNode, $node);
        }
      }
    }
  }

  private function link($y, $x) {
    // Remove $y from the root list
    $y->left->right = $y->right;
    $y->right->left = $y->left;
  
    // Make $y a child of $x
    $y->parent = $x;
    if ($x->child === null) {
      // If $x has no children, set $y as its only child
      $x->child = $y;
      $y->left = $y;
      $y->right = $y;
    } else {
      // Insert $y into $x's child list
      $y->left = $x->child;
      $y->right = $x->child->right;
      $x->child->right->left = $y;
      $x->child->right = $y;
    }
  
    // Increment $x's degree and mark $y as not marked
    $x->degree++;
    $y->marked = false;
  }
}

// test-case
$fibonacciHeap = new FibonacciHeap();

// Test 1: Insert values and extract the minimum
$fibonacciHeap->insert(10);
$fibonacciHeap->insert(4);
$fibonacciHeap->insert(15);
echo $fibonacciHeap->extractMin() . "\n"; // Expected: 4

// Test 2: Insert more values and extract the minimum
$fibonacciHeap->insert(1);
$fibonacciHeap->insert(20);
echo $fibonacciHeap->extractMin() . "\n"; // Expected: 1

// Test 3: Extract from an empty heap (should return null)
$emptyHeap = new FibonacciHeap();
echo $emptyHeap->extractMin() === null ? "Heap is empty\n" : "Error\n"; // Expected: Heap is empty

?>