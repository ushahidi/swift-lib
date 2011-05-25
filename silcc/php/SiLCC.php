<?php

/**
 * PHP Bindings to the SiLCC API
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @link    http://swiftly.org/
 * @link    http://opensilcc.com/
 * @link    https://github.com/ushahidi/SiLCC
 * @link    https://github.com/ushahidi/SiLCC-PHP-Binding
 */
class SiLCC
{
    /**
     * The API key
     *
     * @access public
     * @var    string
     */
    public $key = '';

    /**
     * The API URL
     *
     * @access public
     * @var    string
     */
    public $url;

    /**
     * Constructs a new SiLCC object.
     *
     * @access public
     * @param  string $key The API key
     * @param  string $url The API URL
     */
    public function __construct($key='', $url='http://opensilcc.com/api/tag')
    {
        $this->key = $key;
        $this->url = $url;
    }

    /**
     * Finds tags for the text provided.
     *
     * @access public
     * @param  string $text The text to process for tags
     * @return array  The tags found
     */
    public function tag($text)
    {
        // Perform basic sanity checks on parameters.
        if (strlen($text) === 0) {
            return array();
        }
        if (strlen($this->url) === 0) {
            throw new Exception('SiLCC: Please provide the URL.');
        }

        // Compile associative array of parameters.
        $params = array('text' => $text, 'key' => $this->key);

        // Execute remote API call.
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($curl);

        // Check if a result has been returned.
        if ($result === FALSE) {
            throw new Exception('SiLCC Call Failure: ' . curl_error($curl));
        }

        // Close CURL handle resource.
        curl_close($curl);

        // Decode returned data.
        $tags = json_decode($result);

        // Check if data could be properly decoded.
        $error = json_last_error();

        if ($error != JSON_ERROR_NONE) {
            $errors = array();
            $errors[JSON_ERROR_DEPTH] = 'The maximum stack depth has been exceeded';
            $errors[JSON_ERROR_CTRL_CHAR] = 'Control character error, possibly incorrectly encoded';
            $errors[JSON_ERROR_STATE_MISMATCH] = 'Invalid or malformed JSON';
            $errors[JSON_ERROR_SYNTAX] = 'Syntax error';
            $errors[JSON_ERROR_UTF8] = 'Malformed UTF-8 characters, possibly incorrectly encoded';

            throw new Exception('SiLCC Response Deserialisation Error: ' . $errors[$error] . ' (' . $result . ')');
        }

        return $tags;
    }
}
