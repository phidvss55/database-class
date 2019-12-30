<?php

require('database.class.php');

$config = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'name' => 'livestream'
];

$db = new database($config);

// $db->table('users')->insert([
//     'username' => 'user123',
//     'name' => 'Mai Hoang Long'   
// ]);

// $db->table('users')->deleteId(5);

// $users = $db->table('users')->limit(10)->get();

// foreach($users as $user) {
//     echo $user->name;
//     echo '<br>';
// }

// $users = $db->table('users')->updateRow(6, [
//     'username' => 'admin',
//     'name' => 'Nguyen Minh Duc'
// ]);

?>