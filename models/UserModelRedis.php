<?php

require_once 'BaseModel.php';

class UserModelRedis extends BaseModel {

    private $redis;

    public function __construct() {
        if (!class_exists('Redis')) {
            throw new Exception('Redis extension is not installed or enabled in PHP.');
        }
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
        $this->redis->auth('mypassword'); // Authenticate with password from docker-compose.yml
    }

    public function findUserById($id) {
        $userData = $this->redis->get('user:' . $id);
        return $userData ? json_decode($userData, true) : null;
    }

    public function insertUser($input) {
        $id = $this->redis->incr('user_id_counter'); // Auto-increment ID
        $userData = [
            'id' => $id,
            'name' => $input['name'],
            'password' => md5($input['password'])
        ];
        $this->redis->set('user:' . $id, json_encode($userData));
        return $userData;
    }

    public function updateUser($input) {
        $userData = [
            'id' => $input['id'],
            'name' => $input['name'],
            'password' => md5($input['password'])
        ];
        $this->redis->set('user:' . $input['id'], json_encode($userData));
        return $userData;
    }

    public function deleteUserById($id) {
        return $this->redis->del('user:' . $id);
    }

    public function getUsers($params = []) {
        $keys = $this->redis->keys('user:*');
        $users = [];
        foreach ($keys as $key) {
            $userData = json_decode($this->redis->get($key), true);
            if ($userData) {
                // Filter by keyword if provided
                if (!empty($params['keyword'])) {
                    if (stripos($userData['name'], $params['keyword']) === false) {
                        continue;
                    }
                }
                // Remove sensitive data like password
                unset($userData['password']);
                $users[] = $userData;
            }
        }
        return $users;
    }

    public function auth($userName, $password) {
        $keys = $this->redis->keys('user:*');
        foreach ($keys as $key) {
            $userData = json_decode($this->redis->get($key), true);
            if ($userData['name'] === $userName && $userData['password'] === md5($password)) {
                return $userData;
            }
        }
        return [];
    }
}

?>
