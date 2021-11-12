<?php
use PHPUnit\Framework\TestCase;
import ('plugins.generic.boriSharing.classes.Person');

class PersonTest extends TestCase {
    
    private $person;
    private $name = "Jimi Hendrix";
    private $email = "jimi.hendrix@woodstock.com";

    public function setUp(): void {
        $this->person = new Person($this->name, $this->email);
    }

    public function testPersonHasName(): void {
        $this->assertEquals($this->name, $this->person->getName());
    }

    public function testPersonHasEmail(): void {
        $this->assertEquals($this->email,$this->person->getEmail());
    }

    public function testPersonAsRecord(): void {
        $expectedRecord = "Jimi Hendrix (jimi.hendrix@woodstock.com)";
        $this->assertEquals($expectedRecord,$this->person->asRecord());
    }
}