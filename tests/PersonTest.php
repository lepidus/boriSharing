<?php
use PHPUnit\Framework\TestCase;
import ('plugins.generic.boriSharing.classes.Person');

class PersonTest extends TestCase {
    
    private $person;
    private $name = "Jimi Hendrix";
    private $email = "jimi.hendrix@woodstock.com";
    private $affiliation = "MCA Records";

    public function setUp(): void {
        $this->person = new Person($this->name, $this->email, $this->affiliation);
    }

    public function testPersonHasName(): void {
        $this->assertEquals($this->name, $this->person->getName());
    }

    public function testPersonHasEmail(): void {
        $this->assertEquals($this->email,$this->person->getEmail());
    }

    public function testPersonHasAffiliation(): void {
        $this->assertEquals($this->affiliation,$this->person->getAffiliation());
    }

    public function testPersonAsRecord(): void {
        $expectedRecord = "Jimi Hendrix (jimi.hendrix@woodstock.com) - MCA Records";
        $this->assertEquals($expectedRecord,$this->person->asRecord());
    }
}