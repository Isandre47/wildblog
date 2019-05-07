<?php
/**
 * This file hold all routes definitions.
 *
 * PHP version 7
 *
 * @author   WCS <contact@wildcodeschool.fr>
 *
 * @link     https://github.com/WildCodeSchool/simple-mvc
 */

$routes = [

    'Admin' => [
        ['showDashboard', '/admin/dashboard', 'GET'], //show dashboard for admin
        ['adminShow', '/admin/article/{id:\d+}', 'GET'],//show article for admin
        ['add', '/admin/createArticle', ['GET', 'POST']], // add an article admin
        ['edit', '/admin/article/edit/{id:\d+}', ['GET', 'POST']], //modify an article admin
        ['delete', '/admin/article/delete/{id:\d+}', 'GET'], // delete an article admin
        ['indexAdmin', '/admin/articles', 'GET'], //show index for admin
        ['logAdmin', '/admin/logAdmin', ['GET', 'POST']],
        ['logout', '/admin/logout', 'GET'],//logout
        ['userShow', '/admin/user/{id:\d+}', 'GET'],
        ['usersIndex', '/admin/users', 'GET'],
        ['userDelete', '/admin/user/delete/{id:\d+}', 'GET'],
    ],

    'AdminComment' => [
        ['delete', '/admin/comment/delete/{id:\d+}', 'GET'],//add comment by user
        ['add', '/article/{id:\d+}/comment', 'POST'],    //add comment by user
        ['resetSignal', '/admin/comment/reset/{id:\d+}', 'GET'],    //add comment by user
        ['addCommentSignal', '/article/comment/signal/{id:\d+}', 'GET'],    //add comment signal by user
        ['indexAdminComments', '/admin/comments', 'GET'], //show index comment for admin
        ['indexAdminCommentsSignals', '/admin/comments/signals', 'GET'], //show index comment for admin
    ],

    'User' => [
        ['suscribeUser', '/register', ['GET','POST']], // Register page
        ['logUser', '/login', ['GET','POST']], //  Login page
        ['addUser', '/admin/user/createUser', ['GET', 'POST']],
        ['logoutUser', '/logout', 'GET'],//logout
    ],

    'Article' => [
        ['index', '/articles', 'GET'], //show index to users
        ['show', '/article/{id:\d+}', 'GET'], //show article to users
        ['showbycat', '/article/category/{id:\d+}', 'GET'], //show article to users by category
        ['indexAccueil', '/', 'GET'], //show homepage to users
    ],

    'Portfolios' => [
        ['groupe1', '/Portfolios/groupe1/', 'GET'], //show portfolio to groupe 1
        ['groupe2', '/Portfolios/groupe2/', 'GET'], //show portfolio to groupe 2
        ['groupe3', '/Portfolios/groupe3/', 'GET'], //show portfolio to groupe 3
        ['groupe4', '/Portfolios/groupe4/', 'GET'], //show portfolio to groupe 4
        ['groupe5', '/Portfolios/groupe5/', 'GET'], //show portfolio to groupe 5
        ['groupe6', '/Portfolios/groupe6/', 'GET'], //show portfolio to groupe 6
        ['groupe7', '/Portfolios/groupe7/', 'GET'], //show portfolio to groupe 7
        ['groupe8', '/Portfolios/groupe8/', 'GET'], //show portfolio to groupe 8
        ['groupe9', '/Portfolios/groupe9/', 'GET'], //show portfolio to groupe 9
    ],
];

