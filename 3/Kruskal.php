<?php

  /**
   * 231232028 - Falmesino Abdul Hamid
   * 3. Implementasikan algoritma pencarian jalur seperti Dijkstra dan Kruskal menggunakan priority queue.
   */

  class Kruskal {
    private $edges;
    private $parent;

    public function __construct($edges) {
      $this->edges = $edges;
    }

    public function findMST() {
      usort($this->edges, function($a, $b) {
        return $a[2] - $b[2];
      });

      $this->parent = [];
      foreach ($this->edges as $edge) {
        $this->parent[$edge[0]] = $edge[0];
        $this->parent[$edge[1]] = $edge[1];
      }

      $mst = [];
      foreach ($this->edges as $edge) {
        $u = $edge[0];
        $v = $edge[1];
        if ($this->find($u) !== $this->find($v)) {
          $mst[] = $edge;
          $this->union($u, $v);
        }
      }

      return $mst;
    }

    private function find($node) {
      if ($this->parent[$node] !== $node) {
        $this->parent[$node] = $this->find($this->parent[$node]);
      }
      return $this->parent[$node];
    }

    private function union($u, $v) {
      $rootU = $this->find($u);
      $rootV = $this->find($v);
      $this->parent[$rootU] = $rootV;
    }
  }

  // Test Case untuk Kruskal
  $edges = [
    ['A', 'B', 1],
    ['A', 'C', 4],
    ['B', 'C', 2],
    ['B', 'D', 5],
    ['C', 'D', 1]
  ];

  $kruskal = new Kruskal($edges);
  $mst = $kruskal->findMST();

  echo "Minimum Spanning Tree (MST) menggunakan Kruskal:\n";
  foreach ($mst as $edge) {
    echo $edge[0] . " -- " . $edge[1] . " == " . $edge[2] . "\n";
  }
?>