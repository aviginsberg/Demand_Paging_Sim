<?php
/**
 * Author: Avi Ginsberg
 * Course: CS438
 * IDE: PhpStorm.
 * Date: 9/18/15
 */
if(!isset($_POST['mem_size'],$_POST['page_size'])){
    echo '<html><head><meta name="viewport" content="initial-scale=1"></head><body>
<div style="font-size:1.3em;">Demand Paging Simulation (Assignment 1)<br> CS438 - OS Analysis<br>By Avi Ginsberg (s0753107)</div>
<div>This will simulate demand paging, using the settings below, with the following sequence of requests for program words from a 460-word program: 10, 11, 104, 170, 73, 309, 185, 245, 246, 434, 458, 364.  </div>
<br><br><b>Simulation Settings:</b><br><br>
<form action="index.php" method="post">
Page Size:<br>
<input type="radio" name="page_size" value="20">20<br>
<input type="radio" name="page_size" value="100">100<br>
<input type="radio" name="page_size" value="200">200<br><br>
Memory Size:<br>
<input type="radio" name="mem_size" value="200">200<br>
<input type="radio" name="mem_size" value="400">400<br><br>
<input type="submit" value="Submit">
</form>
</body>
</html>';
}else{
    require_once("DPS.php");
    header('Content-Type:text/plain');

    $program_total_words = 460;
    $word_request_sequence = Array("10", "11", "104", "170", "73", "309", "185", "245", "246", "434", "458", "364");
    $total_mem_size = $_POST['mem_size'];
    $page_size = $_POST['page_size'];

    $DPS = new DPS($page_size,$total_mem_size,$program_total_words, $word_request_sequence);




    $DPS->do_sim();
}
