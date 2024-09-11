<?php

namespace App\WebSockets;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Models\UserToken;
class WebSocketServer implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    // Store the mapping of connection resource IDs to user IDs
    protected $connections = [];
    private $websocketSecret= "secretCodeForGearmanToWebsocet";

    public function onMessage(ConnectionInterface $conn, $msg)
    {
        $data = json_decode($msg, true);

        // Handle login
        if (isset($data['login'])) {
            $user_id = UserToken::getUserByToken($data['login']);

            if ($user_id) {
                // Associate user ID with connection
                $this->connections[$conn->resourceId] = $user_id;
                echo "User $user_id connected.\n";
                $conn->send(json_encode(["message"=>"hello"]));
            } else {
                $conn->send('Invalid token');
            }
        }
        if (isset($data['gearmanCode'])) {
            if($this->websocketSecret==$data['gearmanCode']){
                echo "gearman is talking\n";
                if(isset($data['action'])&&$data['action']=="broadcast"){
                    $this->broadcast($data['msg'], $data['user']);
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Get the user associated with this connection
        if (isset($this->connections[$conn->resourceId])) {
            $user_id = $this->connections[$conn->resourceId];
            echo "User $user_id disconnected.\n";

            // Delete the user's token
            UserToken::deleteToken($this->connections[$conn->resourceId]);

            // Remove the connection from the list
            unset($this->connections[$conn->resourceId]);
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    // Function to broadcast a message to all connected clients
    public function broadcast($msg, $user_id = null)
    {
        // If a user_id is provided, send only to the specified user
        if ($user_id !== null) {
            foreach ($this->connections as $conn => $connectedUserId) {
                if ($connectedUserId == $user_id) {
                    // Find the connection by matching the resourceId
                    foreach ($this->clients as $client) {
                        echo $conn . "\n";
                        echo $client->resourceId . "\n";
                        // Check if $client is an object and has the resourceId property
                        if ($client->resourceId == $conn) {
                            $worked = $client->send($msg);
                            // $worked = $client->send($msg);
                            // $worked = $client->send($msg);
                            echo "Sent message to user $user_id. is \n";
                            return; // Stop once we find and send to the correct user
                        }
                    }
                }
            }
            echo "User $user_id not connected.\n";
        } else {
            // If no user_id is provided, broadcast to all clients
            foreach ($this->clients as $client) {
                // Check if $client is an object and has the resourceId property
                if (is_object($client) && property_exists($client, 'resourceId')) {
                    $client->send($msg);
                }
            }
            echo "Broadcasted message to all clients.\n";
        }
    }


    // Example of server pushing data to all clients or a specific client
    public function pushDataToClients($data, $user_id = null)
    {
        $message = json_encode($data);

        // Pass user_id to the broadcast method
        $this->broadcast($message, $user_id);

        if ($user_id !== null) {
            echo "Server pushed data to user $user_id: " . $message . "\n";
        } else {
            echo "Server pushed data to all clients: " . $message . "\n";
        }
    }

}
