<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use App\WebSockets\WebSocketServer;
use App\Models\BroadcastMessage;
use React\Socket\Server as ReactSocket;
use React\EventLoop\Factory as LoopFactory;

class WebSocketServerCommand extends Command
{
    protected $signature = 'websocket:serve';
    protected $description = 'Run the WebSocket server';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting WebSocket server...');

        // Create the event loop
        $loop = LoopFactory::create();

        // Create the WebSocket server instance
        $webSocketServer = new WebSocketServer();

        // Set up the socket server on the desired port and bind it to the loop
        $socket = new ReactSocket('0.0.0.0:8080', $loop);

        // Create the IoServer instance with the loop and socket
        $server = new IoServer(
            new HttpServer(
                new WsServer(
                    $webSocketServer
                )
            ),
            $socket,
            $loop
        );

        // Check for new messages in the database every 10 seconds
        $loop->addPeriodicTimer(10, function() use ($webSocketServer) {
            $messages = BroadcastMessage::all();

            foreach ($messages as $message) {
                // Push the message along with the user_id
                $webSocketServer->pushDataToClients($message->data, $message->user_id);

                // Delete the message after broadcasting
                $message->delete();
            }
        });

        // Start the event loop
        $loop->run();
    }
}
