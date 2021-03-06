<?php
namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;
use Cake\Collection\Collection;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;


/**
 * PagesControllerTest class
 */
class LogsControllerTest extends IntegrationTestCase
{
    public $fixtures = [
        'app.Users',
        'app.User',
        'app.Logs',
    ];

    public function setup() {

        $this->configRequest([
            'environment' => [
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'ad40db557bf3dd377243348a24ed215702b1c596'
            ],
        ]);

        parent::setup();
    }

    public function testFindDefault() {

        $this->post('/find', ['config' => 'logs']);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');

        $this->assertEquals("I'm a message", $result[0]->message);
        $this->assertEquals(4, $result[0]->id);
        $this->assertEquals("I'm a message", $result[1]->message);
        $this->assertEquals(3, $result[1]->id);
    }

    public function testFindBadLogin() {
        $this->configRequest([
          'environment' => [
              'PHP_AUTH_USER' => 'admin',
              'PHP_AUTH_PW' => 'bad_api_key'
          ],
      ]);

      $this->post('/find', ['config' => 'logs']);
      $this->assertResponseCode(401);
    }

    public function testFindNotSendConfig() {
        $this->post('/find');
        $this->assertResponseCode(404);
    }

    public function testFindBadConfig() {
        $this->post('/find', ['config' => 'badconfig']);
        $this->assertResponseCode(404);
    }

    public function testFindLimit() {

        $this->post('/find', ['config' => 'logs', 'limit' => 1]);

        $this->assertResponseOk();
        $this->assertCount(1, $this->viewVariable('logs'));
    }

    public function testFindOrderAsc() {

        $this->post('/find', ['config' => 'logs', 'limit' => 1, 'order' => ['level' => 'ASC']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');

        $this->assertEquals('alert', $result[0]->level);
    }

    public function testFindOrderDesc() {

        $this->post('/find', ['config' => 'logs', 'limit' => 1, 'order' => ['level' => 'DESC']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals('warning', $result[0]->level);
    }

    public function testFindOrderCombine() {

        $this->post('/find', ['config' => 'logs', 'limit' => 1, 'order' => ['level' => 'DESC', 'scope' => 'ASC']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals('warning', $result[0]->level);
        $this->assertNull($result[0]->scope);
    }

    public function testFindStartDate() {

        $this->post('/find', ['config' => 'logs', 'start' => '2016-05-03', 'limit' => 1, 'order' => ['created' => 'ASC']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals(new Time('2016-05-03'), $result[0]->created);
    }

    public function testFindEndDate() {

        $this->post('/find', ['config' => 'logs', 'end' => '2016-05-02', 'limit' => 1, 'order' => ['created' => 'DESC']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals(new Time('2016-05-02'), $result[0]->created);
    }

    public function testFindUserId() {

        $this->post('/find', ['config' => 'logs', 'userIds' => 2, 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals(2, $result[0]->user_id);
    }

    public function testFindUserNull() {

        $this->post('/find', ['config' => 'logs', 'userIds' => null, 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertNull($result[0]->user_id);
    }

    public function testFindUserFalse() {

        $this->post('/find', ['config' => 'logs', 'userIds' => false, 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertNull($result[0]->user_id);
    }

    public function testFindUserArray() {

        $this->post('/find', ['config' => 'logs', 'userIds' => [2,3]]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $col = new Collection($result);

        $this->assertEquals([2,3], array_values(array_unique($col->extract('user_id')->toArray())));
    }

    public function testFindScopeString() {

        $this->post('/find', ['config' => 'logs', 'scopes' => 'plop', 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals('plop', $result[0]->scope);
    }

    public function testFindScopeNull() {

        $this->post('/find', ['config' => 'logs', 'scopes' => null, 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertNull($result[0]->scope);
    }

    public function testFindScopeFalse() {

        $this->post('/find', ['config' => 'logs', 'scopes' => false, 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertNull($result[0]->scope);
    }

    public function testFindScopeArray() {

        $this->post('/find', ['config' => 'logs', 'scopes' => ['test', 'tele'], 'order' => ['id' => 'ASC']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $col = new Collection($result);

        $this->assertEquals(['test', 'tele'], array_values(array_unique($col->extract('scope')->toArray())));
    }

    public function testFindScopeEmptyArray() {

        $this->post('/find', ['config' => 'logs', 'scopes' => []]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');

        $Logs = TableRegistry::get('Logs');
        $query = $Logs->find();

        $this->assertEquals($query->count(), count($result));
    }

    public function testFindLevelString() {

        $this->post('/find', ['config' => 'logs', 'levels' => 'warning', 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals('warning', $result[0]->level);
    }

    public function testFindLevelArray() {

        $this->post('/find', ['config' => 'logs', 'levels' => ['warning', 'error']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $col = new Collection($result);

        $this->assertEquals(['warning', 'error'], array_values(array_unique($col->extract('level')->toArray())));
    }

    public function testFindLevelEmptyArray() {

        $this->post('/find', ['config' => 'logs', 'levels' => []]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');

        $Logs = TableRegistry::get('Logs');
        $query = $Logs->find();

        $this->assertEquals($query->count(), count($result));
    }

    /**
      PROBLEM WITH FILTER CONTEXT : POST TRAITMENT FAILED WHITH PAGINATION
    */
    /*
    public function testFindContextStringValue() {

        $this->post('/find', ['config' => 'logs', 'context' => ['ip'=>'55.44.11.22']]);

        $this->assertResponseOk();

        $col = new Collection($this->viewVariable('logs'));

        $this->assertEquals(['55.44.11.22/Pages', '55.44.11.22/Blog'], array_values(array_unique($col->extract('message')->toArray())));
    }

    public function testFindContextMultiStringValue() {

        $this->post('/find', ['config' => 'logs', 'context' => ['ip'=>'55.44.11.22', 'name' => 'Pages']]);

        $this->assertResponseOk();

        $col = new Collection($this->viewVariable('logs'));

        $this->assertEquals(['55.44.11.22/Pages'], array_values(array_unique($col->extract('message')->toArray())));
    }*/

    public function testFindReturnUsername() {
        $this->post('/find', ['config' => 'logs']);

        $this->assertResponseOk();

        $expected = ['firstname' => 'Richard', 'lastname' => 'Coeur de chien'];
        $log = $this->viewVariable('logs')[0];

        $this->assertEquals($expected, $log['user']);
    }

    public function testFindByMessage() {
        $this->post('/find', ['config' => 'logs', 'message' => 'warning test']);

        $this->assertResponseOk();

        $logs = $this->viewVariable('logs');
        $firstLog = $logs[0];

        $this->assertCount(1, $logs);
        $this->assertEquals("I'm a warning test", $firstLog->message);
    }

    public function testFindByUserName_firstname() {
        $this->post('/find', ['config' => 'logs', 'user' => 'Richard']);

        $this->assertResponseOk();

        $logs = $this->viewVariable('logs');
        $col = new Collection($logs);

        $this->assertEquals([1], array_values(array_unique($col->extract('user_id')->toArray())));
    }

    public function testFindByUserName_lastname() {
        $this->post('/find', ['config' => 'logs', 'user' => 'Sweppeszeneguer']);

        $this->assertResponseOk();

        $logs = $this->viewVariable('logs');
        $col = new Collection($logs);

        $this->assertEquals([7], array_values(array_unique($col->extract('user_id')->toArray())));
    }

    public function testFindByUserName_firstnameAndLastname() {
        $this->post('/find', ['config' => 'logs', 'user' => 'Mat Dumon']);

        $this->assertResponseOk();

        $logs = $this->viewVariable('logs');
        $col = new Collection($logs);

        $this->assertEquals([4], array_values(array_unique($col->extract('user_id')->toArray())));
    }

    public function testFindByUserName_firstnameComposed() {
        $this->post('/find', ['config' => 'logs', 'user' => 'Jean Claude']);

        $this->assertResponseOk();

        $logs = $this->viewVariable('logs');
        $col = new Collection($logs);

        $this->assertEquals([3], array_values(array_unique($col->extract('user_id')->toArray())));
    }

    public function testFindByUserName_lastnameComposed() {
        $this->post('/find', ['config' => 'logs', 'user' => 'Van Dome']);

        $this->assertResponseOk();

        $logs = $this->viewVariable('logs');
        $col = new Collection($logs);

        $this->assertEquals([3], array_values(array_unique($col->extract('user_id')->toArray())));
    }

    public function testFindByUserName_empty() {
        $this->post('/find', ['config' => 'logs', 'user' => '']);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');

        $Logs = TableRegistry::get('Logs');
        $query = $Logs->find();

        $this->assertEquals($query->count(), count($result));
    }




    public function testGetConfigs() {
      $this->post('/configs');

      $this->assertResponseOk();

      $result = $this->viewVariable('configs');

      $this->assertEquals(['logs'], $result);
    }

    public function testGetConfigs_badLogin() {

      $this->configRequest([
          'environment' => [
              'PHP_AUTH_USER' => 'admin',
              'PHP_AUTH_PW' => 'bad_api_key'
          ],
      ]);

      $this->post('/configs');
      $this->assertResponseCode(401);
    }
}
