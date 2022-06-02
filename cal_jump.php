<table cellspacing="0px" width="100%" class="printhide">
<tr>
    <td class="vmiddle addheader post_row_0 pad hpad8">Date Jump:</td>
	<td class="vmiddle hpad8 post_row_1"><?php echo  menu_month($thismonth,$thisyear,make2($thisday),"month.php"); ?></td>
    <td class="vmiddle hpad8 post_row_1"><form action="week.php" method="get" name="SelectWeek">
<span class="addheader">&nbsp;&nbsp;&nbsp;&nbsp;Week: </span>
<select name="date" onchange="document.SelectWeek.submit()" class="txtinput vmiddle">
<?php
  if ( ! empty ( $thisyear ) && ! empty ( $thismonth ) ) {
    $m = $thismonth;
    $y = $thisyear;
  } else {
    $m = date ( "m" );
    $y = date ( "Y" );
  }
  if ( ! empty ( $thisday ) ) {
    $d = $thisday;
  } else {
    $d = date ( "d" );
  }
  $d_time = mktime ( 3, 0, 0, $m, $d, $y );
  $thisdate = date ( "Ymd", $d_time );
  $wday = date ( "w", $d_time );
  $wkstart = mktime ( 3, 0, 0, $m, $d - $wday, $y );
  for ( $i = -7; $i <= 7; $i++ ) {
    $twkstart = $wkstart + ( 3600 * 24 * 7 * $i );
    $twkend = $twkstart + ( 3600 * 24 * 6 );
    echo "<option value=\"" . date ( "Ymd", $twkstart ) . "\"";
    if ( date ( "Ymd", $twkstart ) <= $thisdate &&
      date ( "Ymd", $twkend ) >= $thisdate )
      echo " selected";
    echo ">";
    printf ( "%s - %s",
      date_to_str ( date ( "Ymd", $twkstart ), $DATE_FORMAT_MD, false, true ),
      date_to_str ( date ( "Ymd", $twkend ), $DATE_FORMAT_MD, false, true ) );
    echo "\n";
  }
?>
</select>
</form></td>

    <td class="vmiddle hpad8 post_row_1"><?php  echo menu_day($thismonth,$thisyear,make2($thisday),"day.php"); ?></td>
</tr>
</table>









