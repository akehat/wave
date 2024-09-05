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
            } else {
                $conn->send('Invalid token');
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
            foreach ($this->connections as $resourceId => $connectedUserId) {
                if ($connectedUserId == $user_id) {
                    // Find the connection associated with this user_id
                    if (isset($this->clients[$resourceId])) {
                        $this->clients[$resourceId]->send($msg);
                        echo "Sent message to user $user_id.\n";
                    }
                    return; // Stop once we find and send to the correct user
                }
            }
            echo "User $user_id not connected.\n";
        } else {
            // If no user_id is provided, broadcast to all clients
            foreach ($this->clients as $client) {
                $client->send($msg);
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
