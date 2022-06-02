<?php
if(preg_match("/wbm_footer.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Footer section for UzuriWeb
**/

echo '<table width="950"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td width="17">&nbsp;</td>
</tr>
</table>
</body>
</html>';
?>