<?php namespace Builder\Users;


class UserCreator {

    private $app;
    private $sentry;

    /**
     * Create new UserCreator instance.
     *
     * @param $app
     */
    public function __construct($app) {
        $this->app = $app;
        $this->sentry = $app['sentry'];
    }

    /**
     * Create a new user from given credentials.
     *
     * @param array $credentials
     * @return UserModel
     */
    public function create($credentials) {

        $user = $this->sentry->register([
            'email'       => $credentials['email'],
            'password'    => $credentials['password'],
            'permissions' => json_decode($this->app['settings']['permissions'], true)
        ], true);

        //log new user in, if we don't have a user logged in already
        if ( ! $this->sentry->check()) {
            $user = $this->sentry->authenticate($this->input->all(), true);
        }

        return $user;
    }
}