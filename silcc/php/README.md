This is the official PHP bindings for the SiLCC web service. Currently, only the ``tag`` method is supported.

Example of use:

    <?php
    require_once 'SiLCC.php';
    $SiLCC = new SiLCC();
    $tags = $SiLCC->tag('The quick brown fox jumps over the lazy dog');
    var_dump($tags);

This should output something similar to:

    array(6) {
      [0]=>
      string(5) "quick"
      [1]=>
      string(5) "brown"
      [2]=>
      string(3) "fox"
      [3]=>
      string(4) "jump"
      [4]=>
      string(4) "lazy"
      [5]=>
      string(3) "dog"
    }

Please note that you can only process 1000 calls per day without using an API key.

This library is released under the [GNU Lesser General Public License](http://www.gnu.org/copyleft/lesser.html).

For more information regarding SiLCC, please see:

* http://swiftly.org/
* http://opensilcc.com/
* https://github.com/ushahidi/SiLCC