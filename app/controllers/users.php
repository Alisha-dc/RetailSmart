<?php
require_once __DIR__ . '/../models/Users.php';

//Change user role

function change_user_role(
    int $userId,
    string $role
):bool{

if(!in_array(
    $role,
    ['customer','admin'],
    true
)){
    return false;
}
$result = User::updateRole(
    $userId,
    $role
);

if($result){
    log_action(
        'User Role Updated',
        "User ID : $userId,Role:$role"
    );
}
return $result;
}

//Delete user

function delete_user(
    int $userId
): bool{
    //Do not allow deleting yourself 

    $current=current_user();
    if (
        $current &&
        (int)$current['id'] === $userId
    ) {
        return false;
    }

    $result =
    User::delete($userId);
    if($result){

    log_action(
        'User Deleted',
        "User ID :$userId"
    );
    }
    return $result;
}