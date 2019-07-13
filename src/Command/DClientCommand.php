<?php declare(strict_types=1);

namespace Swoft\Devtool\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Console\Helper\Show;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoole\Coroutine\Client;
use const SWOOLE_SOCK_TCP;

/**
 * Provide some simple tcp, ws client for develop or testing[by <cyan>devtool</cyan>]
 *
 * @Command("dclient")
 */
class DClientCommand
{
    /**
     * connect to an tcp server and allow send message interactive
     *
     * @CommandMapping()
     * @CommandOption("host", short="H", desc="the tcp server host address", default="127.0.0.1", type="string")
     * @CommandOption("port", short="p", desc="the tcp server port number", default="18309", type="integer")
     *
     * @param Input  $input
     * @param Output $output
     */
    public function tcp(Input $input, Output $output): void
    {
        $client = new Client(SWOOLE_SOCK_TCP);
        $client->set([
            'open_eof_check' => true,
            'package_eof'    => "\r\n\r\n",
            'package_max_length' => 1024 * 1024 * 2,
        ]);

        $host = $input->getSameOpt(['host', 'H'], '127.0.0.1');
        $port = $input->getSameOpt(['port', 'p'], 18309);

        CONNCET:
        if (!$ok = $client->connect((string)$host, (int)$port, 5.0)) {
            $code = $client->errCode;
            /** @noinspection PhpComposerExtensionStubsInspection */
            $msg = socket_strerror($code);
            Show::error("Connect server failed. Error($code): $msg");
            return;
        }

        $addr = $host . ':' . $port;
        $output->colored('Successful connect to tcp server ' . $addr, 'success');

        while (true) {
            if (!$msg = $input->read('> ')) {
                $output->liteWarning('Please input message for send');
                continue;
            }

            // Exit interactive terminal
            if ($msg === 'quit' || $msg === 'exit') {
                $output->colored('Quit, Bye!');
                break;
            }

            if (false === $client->send($msg)) {
                /** @noinspection PhpComposerExtensionStubsInspection */
                $output->error('Send error - ' . socket_strerror($client->errCode));

                $yes = $input->read('Reconnect? y/n: ');
                if ($yes && strpos($yes, 'y') === 0) {
                    $client->close();
                    goto CONNCET;
                }

                $output->colored('GoodBye!');
                break;
            }

            $res = $client->recv();
            $output->writef('Return: %s', $res);
        }

        $client->close();
    }
}
