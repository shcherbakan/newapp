<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'Laravel');

// Project repository
set('repository', 'git@github.com:shcherbakan/newapp.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', ['.env']);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts

host('devkvm.top')
    ->user('laravel')
    ->stage('production')
    ->set('deploy_path', '/var/www/laravel');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('argon:link', function () {
    run('cd {{release_path}}/public && ln -s ../resources/argon');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
after('deploy:prepare', 'argon:link');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');
