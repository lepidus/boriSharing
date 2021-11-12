<?php

class SubmissionToShare {

    private $id;
    private $title;
    private $abstract;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title) {
        $this->title = $title;
    }

    public function getAbstract(): string {
        return $this->abstract;
    }

    public function setAbstract(string $abstract) {
        $this->abstract = $abstract;
    }

}