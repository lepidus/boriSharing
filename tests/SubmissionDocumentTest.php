<?php
use PHPUnit\Framework\TestCase;
import ('plugins.generic.boriSharing.classes.SubmissionDocument');

class SubmissionDocumentTest extends TestCase {
    
    private $document;
    private $path = "journals/12/articles/0000/submission/proof/20210816.pdf";
    private $name = "Artigo versão definitiva";

    public function setUp(): void {
        $this->document = new SubmissionDocument($this->path, $this->name);
    }

    public function testDocumentHasPath(): void {
        $this->assertEquals($this->path, $this->document->getPath());
    }

    public function testDocumentHasName(): void {
        $this->assertEquals($this->name,$this->document->getName());
    }

}