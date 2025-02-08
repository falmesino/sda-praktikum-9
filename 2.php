<?php

  /**
   * 231232028 - Falmesino Abdul Hamid
   * 2. Buat priority queue menggunakan heap dan implementasikan operasi enqueue, dequeue, dan peek
   */


  class PriorityQueue {
    private $heap;

    public function __construct() {
      $this->heap = [];
    }

    private function parent($index) {
      return ($index - 1) >> 1;
    }

    private function leftChild($index) {
      return ($index << 1) + 1;
    }

    private function rightChild($index) {
      return ($index << 1) + 2;
    }

    private function swap($i, $j) {
      $temp = $this->heap[$i];
      $this->heap[$i] = $this->heap[$j];
      $this->heap[$j] = $temp;
    }

    private function heapifyUp($index) {
      while ($index > 0 && $this->heap[$this->parent($index)] < $this->heap[$index]) {
        $this->swap($index, $this->parent($index));
        $index = $this->parent($index);
      }
    }

    private function heapifyDown($index) {
      $maxIndex = $index;
      $left = $this->leftChild($index);
      $right = $this->rightChild($index);

      if ($left < count($this->heap) && $this->heap[$left] > $this->heap[$maxIndex]) {
        $maxIndex = $left;
      }

      if ($right < count($this->heap) && $this->heap[$right] > $this->heap[$maxIndex]) {
        $maxIndex = $right;
      }

      if ($index !== $maxIndex) {
        $this->swap($index, $maxIndex);
        $this->heapifyDown($maxIndex);
      }
    }

    public function enqueue($value) {
      array_push($this->heap, $value);
      $this->heapifyUp(count($this->heap) - 1);
    }

    public function dequeue() {
      if (count($this->heap) === 0) {
        throw new Exception("Queue is empty");
      }
      $max = $this->heap[0];
      $last = array_pop($this->heap);
  
      if (!empty($this->heap)) {
        $this->heap[0] = $last;
        $this->heapifyDown(0);
      }
  
      return $max;
    }  

    public function peek() {
      if (count($this->heap) === 0) {
        throw new Exception("Queue is empty");
      }
      return $this->heap[0];
    }

    public function isEmpty() {
      return count($this->heap) === 0;
    }

    public function size() {
      return count($this->heap);
    }
  }

  // Test cases
  $pq = new PriorityQueue();

  $pq->enqueue(10);
  $pq->enqueue(20);
  $pq->enqueue(15);
  $pq->enqueue(30);
  $pq->enqueue(5);

  echo "Peek: " . $pq->peek() . "\n"; // Should print 30

  echo "Dequeue: " . $pq->dequeue() . "\n"; // Should print 30
  echo "Dequeue: " . $pq->dequeue() . "\n"; // Should print 20

  echo "Peek after dequeue: " . $pq->peek() . "\n"; // Should print 15

  $pq->enqueue(40);
  echo "Peek after enqueue 40: " . $pq->peek() . "\n"; // Should print 40

  echo "Size of queue: " . $pq->size() . "\n"; // Should print 4

  while (!$pq->isEmpty()) {
    echo "Dequeue: " . $pq->dequeue() . "\n";
  }

  echo "Size of queue after all dequeues: " . $pq->size() . "\n"; // Should print 0

  ?>