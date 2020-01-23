<?php

use Backend\Domain\Entities\DocumentEntity;
use Backend\Domain\Services\Exceptions\AlreadyPublishedException;
use Backend\Models\Enums\DocumentStatus;
use Backend\Repositories\DocumentModelRepository;
use Backend\Services\DocumentService;
use Carbon\Carbon;

class DocumentTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testCreateDocument()
    {

        $service = new DocumentService(new DocumentModelRepository());

        $document = $service->create();

        $this->tester->assertInstanceOf(DocumentEntity::class, $document);

        $this->tester->assertInstanceOf(Carbon::class, $document->getModifyAt());
        $this->tester->assertInstanceOf(Carbon::class, $document->getCreateAt());

        $this->tester->assertEquals(DocumentStatus::DRAFT()->getValue(), $document->getStatus()->getValue());

        return $document;
    }

    /**
     * @depends testCreateDocument
     */
    public function testFirstUpdateDocument(DocumentEntity $createdDocument)
    {

        $service = new DocumentService(new DocumentModelRepository());

        $payload = json_decode('{"actor":"The fox","meta":{"type":"quick","color":"brown"},"actions":[{"action":"jump over","actor":"lazy dog"}]}');

        $document = $service->change($createdDocument->getId(), $payload);

        $this->tester->assertInstanceOf(DocumentEntity::class, $document);

        $this->tester->assertEquals($payload, $document->getPayload());

        return $document;
    }

    /**
     * @depends testFirstUpdateDocument
     */
    public function testSecondUpdateDocument(DocumentEntity $changedDocument)
    {

        $service = new DocumentService(new DocumentModelRepository());

        $payload = json_decode('{"actor":"The fox","meta":{"type":"quick","color": null},"actions":[{"action":"jump over","actor":"lazy dog"}]}');
        $needPayload = json_decode('{"actor":"The fox","meta":{"type":"quick"},"actions":[{"action":"jump over","actor":"lazy dog"}]}');

        $document = $service->change($changedDocument->getId(), $payload);

        $this->tester->assertInstanceOf(DocumentEntity::class, $document);

        $this->tester->assertEquals($needPayload, $document->getPayload());

        return $document;
    }

    /**
     * @depends testSecondUpdateDocument
     */
    public function testPublishDocument(DocumentEntity $changedDocument)
    {

        $service = new DocumentService(new DocumentModelRepository());

        $document = $service->publish($changedDocument->getId());

        $this->tester->assertEquals(DocumentStatus::PUBLISHED()->getValue(), $document->getStatus()->getValue());

        return $changedDocument;
    }

    /**
     * @depends testPublishDocument
     */
    public function testUpdatePublishedDocument(DocumentEntity $changedDocument)
    {

        $service = new DocumentService(new DocumentModelRepository());

        $this->tester->expectThrowable(AlreadyPublishedException::class, function () use ($service, $changedDocument) {

            $service->change($changedDocument->getId(), new stdClass());

        });
    }

    /**
     * @depends testPublishDocument
     */
    public function testPublishPublishedDocument(DocumentEntity $changedDocument){

        $service = new DocumentService(new DocumentModelRepository());

        $document = $service->publish($changedDocument->getId());

        $this->tester->assertEquals($changedDocument->getPayload(), $document->getPayload());
    }
}