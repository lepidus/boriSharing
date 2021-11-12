<?php
use PHPUnit\Framework\TestCase;
import ('plugins.generic.boriSharing.classes.SubmissionToShare');

error_log("------- TESTES DO BORI SHARING ---------");

class SubmissionToShareTest extends TestCase {

    private $submissionToShare;
    private $submissionId = 7;
    private $submissionTitle = "O caso dos cones mágicos";
    private $submissionAbstract = "Uma história das formas geométricas cônicas e suas aplicações na terra média.";

    public function setUp(): void {
        $this->submissionToShare = new SubmissionToShare();
        $this->submissionToShare->setId($this->submissionId);
        $this->submissionToShare->setTitle($this->submissionTitle);
        $this->submissionToShare->setAbstract($this->submissionAbstract);
    }

    public function testSubmissionHasId(): void {
        $this->assertEquals($this->submissionId, $this->submissionToShare->getId());
    }

    public function testSubmissionHasTitle(): void {
        $this->assertEquals($this->submissionTitle, $this->submissionToShare->getTitle());
    }

    public function testSubmissionHasAbstract(): void {
        $this->assertEquals($this->submissionAbstract, $this->submissionToShare->getAbstract());
    }

}