<?php
namespace App\Shell;

use Cake\Console\Shell;

class UserShell extends Shell {

    public function initialize() {
        parent::initialize();
        $this->loadModel('Users');
    }

    public function add($username = null) {

        if ( ! $username )
            return $this->err('username required');

        $user = $this->Users->newEntity();
        $user = $this->Users->patchEntity($user, ['username' => $username]);

        if ( ! $this->Users->save($user) )
            return $this->err('Save Error');

        $this->success('User created with api key = ' . $user->api_key_plain);
    }

}