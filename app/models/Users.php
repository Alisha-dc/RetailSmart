<?php

//User model , handles database related to users 

class User {

    //Find user by email
    public static function findByEmail(
        string $email
    ): ?array {
        $sql = "
            SELECT * FROM users
            WHERE email = ?
            LIMIT 1
        ";

        $stmt = db()->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function findEmail(
        string $email
    ): ?array {
        return self::findByEmail($email);
    }

//Find user by ID


public static function find(
    int $id
): ?array{
    $stmt =db()->prepare(
        "SELECT * FROM users WHERE id = ?"
    );

    $stmt->execute([$id]);
    $user =$stmt->fetch(PDO::FETCH_ASSOC);
    return $user ?: null;
}

//Create new user 

public static function create (
     string $name,
     string $email,
     string $password
): bool  {

//Hash password 

$hashedPassword=password_hash(
    $password,
    PASSWORD_DEFAULT
);
$sql ="
INSERT INTO users 
(name,email,password)
VALUES(?,?,?)";

$stmt=db()->prepare($sql);
return $stmt->execute ([
    $name,
    $email,
    $hashedPassword
]);
}

//Get all the users 

public static function all():array
{
    $stmt =db()->query(
        "SELECT id,name,email,role,created_at
        FROM users
        ORDER BY id DESC"
    );

    return $stmt-> fetchALL(PDO::FETCH_ASSOC);
}


//Update user role

public static function updateRole(
    int $id,
    string $role
):bool{

$stmt =db()->prepare(
    "UPDATE users 
    SET role=?
    WHERE id =?"
);

return $stmt->execute([
    $role,
    $id
]);
}


//Delete user
public static function delete(
    int $id
) :bool {
    $stmt=db()->prepare(
        "DELETE FROM users
        WHERE ID = ?"
    );
    return $stmt ->execute ([$id]);
}

}
