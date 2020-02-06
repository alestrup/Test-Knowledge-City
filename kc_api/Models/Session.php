<?php
// Class to handle user sessions
class Session
{
    public function create($id_user, $auth_token, $last_activity, $remember)
    {
        $sql = "INSERT INTO sessions (id_user, auth_token, last_activity, remember) VALUES 
            (:id_user,:auth_token,:last_activity,:remember)";
        
        try {
            $db = new Connection();
            $db = $db->connect();
            
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':auth_token', $auth_token);
            $stmt->bindParam(':last_activity', $last_activity);
            $stmt->bindParam(':remember', $remember);

            return $stmt->execute();

        } catch (PDOException $e) {
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
    }

    public function getSession($auth_token, $id_user)
    {
        $sql = "SELECT * FROM sessions WHERE auth_token = '$auth_token'  AND id_user = $id_user";
        try {
            $db = new Connection();
            $db = $db->connect();
            
            $stmt = $db->query($sql);

            $session = $stmt->fetch(PDO::FETCH_ASSOC);

            $db = null; 

            return $session;

        } catch (PDOException $e) {
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
    }

    public function deleteSession($auth_token, $id_user)
    {
        $sql = "DELETE FROM sessions WHERE auth_token = '$auth_token' AND id_user = $id_user";

        try {
            $db = new Connection();
            $db = $db->connect();
            
            $stmt = $db->prepare($sql);
            $stmt->execute();

            return true;

        } catch (PDOException $e) {
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
    }

    public function updateSession($auth_token, $id_user, $last_activity)
    {
        $sql = "UPDATE sessions SET last_activity = :last_activity WHERE auth_token = :auth_token AND id_user = :id_user";
        try {
            $db = new Connection();
            $db = $db->connect();
            
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':last_activity', $last_activity);
            $stmt->bindParam(':auth_token', $auth_token);
            $stmt->bindParam(':id_user', $id_user);

            return $stmt->execute();

        } catch (PDOException $e) {
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
        
    }

    public function deleteAllSessions($id_user){
        $sql = "DELETE FROM sessions WHERE id_user = $id_user";

        try {
            $db = new Connection();
            $db = $db->connect();
            
            $stmt = $db->prepare($sql);
            $stmt->execute();

            $db = null;

            // return $stmt->fetch();

        } catch (PDOException $e) {
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
        
    }
}
