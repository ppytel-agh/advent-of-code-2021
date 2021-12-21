<?php

class CaveNode {
    private bool $isBig;
    private array connectedNodes;

    public function __construct(bool $isBig) {
        this->isBig = $isBig;
    }

    public function connectToNode(CaveNode $otherNode) {
        
    }
}