<?php declare(strict_types=1);


namespace Swoft\Devtool\Helper;


/**
 *
 * @uses     ConsoleHelper
 * @version  2019年06月09日
 * @author   zhenghongyang <sakuraovq@gmail.com>
 * @license  PHP Version 7.1.x {@link http://www.php.net/license/3_0.txt}
 */

/**
 *
 * @uses     ConsoleHelper
 * @version  2019年06月09日
 * @author   zhenghongyang <sakuraovq@gmail.com>
 * @license  PHP Version 7.1.x {@link http://www.php.net/license/3_0.txt}
 */
class ConsoleHelper
{


    /**
     * Send confirm question
     *
     * @param string $question question
     * @param bool   $default  Default value
     *
     * @return bool
     */
    public static function confirm(string $question, $default = true): bool
    {
        if (!$question = trim($question)) {
            \output()->writeln('Please provide a question message!', true);
        }

        $question    = ucfirst(trim($question, '?'));
        $default     = (bool)$default;
        $defaultText = $default ? 'yes' : 'no';
        $message     = "<comment>$question ?</comment>\nPlease confirm (yes|no)[default:<info>$defaultText</info>]: ";

        while (true) {
            \output()->writeln($message, false);
            $answer = \input()->read();

            if (empty($answer)) {
                return $default;
            }

            if (0 === stripos($answer, 'y')) {
                return true;
            }

            if (0 === stripos($answer, 'n')) {
                return false;
            }
        }

        return false;
    }
}
