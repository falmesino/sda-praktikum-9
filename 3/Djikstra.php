<?php

  /**
   * 231232028 - Falmesino Abdul Hamid
   * 3. Implementasikan algoritma pencarian jalur seperti Dijkstra dan Kruskal menggunakan priority queue.
   */

  class Dijkstra {
    private $graph;
    private $distance;
    private $previous;
    private $priorityQueue;

    public function __construct($graph) {
      $this->graph = $graph;
    }

    public function shortestPath($start, $end) {
      $this->distance = array_fill_keys(array_keys($this->graph), INF);
      $this->distance[$start] = 0;
      $this->previous = array_fill_keys(array_keys($this->graph), null);
      $this->priorityQueue = new SplPriorityQueue();
      $this->priorityQueue->insert($start, 0);

      while (!$this->priorityQueue->isEmpty()) {
        $u = $this->priorityQueue->extract();

        if ($u === $end) {
          return $this->getPath($end);
        }

        foreach ($this->graph[$u] as $v => $weight) {
          $alt = $this->distance[$u] + $weight;
          if ($alt < $this->distance[$v]) {
            $this->distance[$v] = $alt;
            $this->previous[$v] = $u;
            $this->priorityQueue->insert($v, -$alt);
          }
        }
      }

      return null;
    }

    private function getPath($end) {
      $path = [];
      while ($end !== null) {
        array_unshift($path, $end);
        $end = $this->previous[$end];
      }
      return $path;
    }
  }

  // test-case
  $graph = [
    'A' => ['B' => 1, 'C' => 4],
    'B' => ['A' => 1, 'C' => 2, 'D' => 5],
    'C' => ['A' => 4, 'B' => 2, 'D' => 1],
    'D' => ['B' => 5, 'C' => 1]
  ];

  $dijkstra = new Dijkstra($graph);
  $start = 'A';
  $end = 'D';
  $path = $dijkstra->shortestPath($start, $end);

  if ($path) {
    echo "Jalur terpendek dari $start ke $end adalah: " . implode(' -> ', $path) . "\n";
  } else {
    echo "Tidak ada jalur dari $start ke $end.\n";
  }
?>