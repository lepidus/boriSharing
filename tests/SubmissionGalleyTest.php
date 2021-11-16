<?php
use PHPUnit\Framework\TestCase;
import ('plugins.generic.boriSharing.classes.SubmissionGalley');

class SubmissionGalleyTest extends TestCase {
    
    private $galley;
    private $path = "journals/12/articles/0000/submission/proof/20210816.pdf";
    private $name = "Artigo versão definitiva";

    public function setUp(): void {
        $this->galley = new SubmissionGalley($this->path, $this->name);
    }

    public function testGalleyHasPath(): void {
        $this->assertEquals($this->path, $this->galley->getPath());
    }

    public function testGalleyHasName(): void {
        $this->assertEquals($this->name,$this->galley->getName());
    }

}