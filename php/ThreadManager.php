<?php

/**
 *
 */
class ThreadManager {

    private $threads;

    public function __construct() {
        $this->threads = array();
    }

    public function addThread($thread) {
        $this->threads[$thread->getThreadId()], $thread);
        return $this;
    }

    public function removeThread($id) {
        unset($this->threads[$id]);
        return $this;
    }
}


// function asdf() {
//
// }

?>
