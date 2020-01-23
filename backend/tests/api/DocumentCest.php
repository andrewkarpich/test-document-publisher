<?php

class DocumentCest
{
    public $document;

    public function createDocument(ApiTester $I)
    {

        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPOST('/document');

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();

        $I->seeResponseJsonMatchesJsonPath('$.document.id');
        $I->seeResponseJsonMatchesJsonPath('$.document.status');
        $I->seeResponseJsonMatchesJsonPath('$.document.payload');
        $I->seeResponseJsonMatchesJsonPath('$.document.createAt');
        $I->seeResponseJsonMatchesJsonPath('$.document.modifyAt');

        $I->seeResponseMatchesJsonType([
            'document' => [
                'id'       => 'string',
                'status'   => 'string',
                'payload'  => 'array',
                'createAt' => 'string:date',
                'modifyAt' => 'string:date',
            ]
        ]);

        $this->document = $I->grabDataFromResponseByJsonPath('$.document')[0];

        $I->assertEquals('draft', $this->document['status']);
    }

    /**
     * @depends createDocument
     */
    public function getDocument(ApiTester $I)
    {

        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendGET('/document/' . $this->document['id']);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();

        $I->assertEquals($this->document['id'], $I->grabDataFromResponseByJsonPath('$.document.id')[0]);

    }

    /**
     * @depends createDocument
     */
    public function firstUpdateDocument(ApiTester $I)
    {

        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPATCH('/document/' . $this->document['id'], [
            'document' => [
                'payload' => [
                    'action' => 'test_test'
                ],
            ]
        ]);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();

        $I->seeResponseContainsJson([
            'document' => [
                'id'       => $this->document['id'],
                'status'   => $this->document['status'],
                'payload'  => [
                    'action' => 'test_test'
                ],
                'createAt' => $this->document['createAt']
            ],
        ]);

        $this->document = $I->grabDataFromResponseByJsonPath('$.document')[0];

    }

    /**
     * @depends createDocument
     */
    public function secondUpdateDocument(ApiTester $I)
    {

        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPATCH('/document/' . $this->document['id'], [
            'document' => [
                'payload' => [
                    'action_2' => 'test_test_2'
                ],
            ]
        ]);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();

        $I->seeResponseContainsJson([
            'document' => [
                'id'       => $this->document['id'],
                'status'   => $this->document['status'],
                'payload'  => [
                    'action'   => 'test_test',
                    'action_2' => 'test_test_2'
                ],
                'createAt' => $this->document['createAt']
            ],
        ]);

        $this->document = $I->grabDataFromResponseByJsonPath('$.document')[0];

    }

    /**
     * @depends createDocument
     */
    public function updateDocumentWithoutPayload(ApiTester $I)
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPATCH('/document/' . $this->document['id'], [
            'document' => []
        ]);

        $I->seeResponseCodeIs(400);
    }

    /**
     * @depends createDocument
     */
    public function publishDocument(ApiTester $I)
    {

        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPOST('/document/' . $this->document['id'] . '/publish');

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();

        $this->document = $I->grabDataFromResponseByJsonPath('$.document')[0];

        $I->assertEquals('published', $this->document['status']);

        $this->document = $I->grabDataFromResponseByJsonPath('$.document')[0];
    }

    /**
     * @depends publishDocument
     */
    public function updatePublisedDocument(ApiTester $I)
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPATCH('/document/' . $this->document['id'], [
            'document' => [
                'payload' => [
                    'action_3' => 'test_test_3'
                ],
            ]
        ]);

        $I->seeResponseCodeIs(400);
    }

    public function publishDocumentWithWrongId(ApiTester $I)
    {

        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPOST('/document/test/publish');

        $I->seeResponseCodeIs(404);
    }

    public function publishDocumentWithNotExistingId(ApiTester $I)
    {

        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPOST('/document/752c563e-875a-4c3f-9151-c95794265359/publish');

        $I->seeResponseCodeIs(404);
    }

    public function getListDocuments(ApiTester $I)
    {

        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendGET('/document');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $I->seeResponseJsonMatchesJsonPath('$.document.[0].id');
        $I->seeResponseJsonMatchesJsonPath('$.document.[0].payload');
    }
}