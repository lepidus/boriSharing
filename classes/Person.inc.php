<?php

class Person {

    private $name;
    private $email;
    private $affiliation;

    public function __construct(string $name, string $email, string $affiliation = "") {
        $this->name = $name;
        $this->email = $email;
        $this->affiliation = $affiliation;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): string {
        return $this->email;
    }
    
    public function getAffiliation(): string {
        return $this->affiliation;
    }

    public function asRecord(): string {
        $record = "{$this->name} ({$this->email})";
        if(!empty($this->affiliation))
            $record .= " - {$this->affiliation}";
        return $record;
    }

}