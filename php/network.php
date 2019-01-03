<?php
$netoutput = shell_exec('cat /proc/net/dev');
$netoutput = explode("\n", $netoutput);
$count = 0;
foreach ($netoutput as $line) {
    if ($count > 1) {
        $lineItems = preg_split('/\s+/', $line);
?>
            <tr>
              <td><?php echo $lineItems[1];?></td>
              <td><?php echo $lineItems[2];?></td>
              <td><?php echo $lineItems[3];?></td>
              <td><?php echo $lineItems[4];?></td>
              <td><?php echo $lineItems[10];?></td>
              <td><?php echo $lineItems[12];?></td>
              <td><?php echo $lineItems[13];?></td>
    		</tr>
<?php 
        }
        $count ++;
    }
?>