<?php

function statistics_title() {
  return _("Statistics");
}

function statistics() {

  $hours_needed = 0;
  $shifts_duration = sql_select("Select `SID`, `RID`, (1 * (`end` - `start`)) as duration FROM Shifts");
  foreach($shifts_duration as $shift) {
    $needs = sql_select("SELECT `count` FROM `NeededAngelTypes` WHERE `shift_id` = "  . $shift["SID"]  . " AND room_id is NULL");
    if (count($needs) == 0) {
      var_dump($needs);
      $needs =  sql_select("SELECT `count` FROM `NeededAngelTypes` WHERE `room_id` = "  . $shift["RID"]);
    }
    foreach($needs as $need) {
      //var_dump($need);
      $hours_needed += $need["count"] * $shift["duration"];
    }
  }

  $table_numbers = array(
    array(_("Shifts"), count(sql_select("SELECT `SID` FROM `Shifts`"))),
    array(_("Hours needed"), $hours_needed / 3600),
    array(_("Angels"), count(sql_select("SELECT `UID` FROM `User`"))),
    array(_("Arrived Angels"), count(sql_select("SELECT `UID` FROM `User` WHERE `Gekommen` = 1"))),
    array(_("Active Angels"), count(sql_select("SELECT `UID` FROM `User` WHERE `Aktiv` = 1 OR `force_active` = 1"))),
    array(_("Angeltypes"), count(sql_select("SELECT `id` FROM `AngelTypes`"))),
    array(_("Shift Entries"), count(sql_select("SELECT `SID` FROM `ShiftEntry`"))),
    array(_("Rooms"), count(sql_select("SELECT `RID` FROM `Room`"))),
    array(_("Log Entries"), count(sql_select("SELECT `id` FROM `LogEntries`"))),
  );

  return page_with_title(_("Statistics"), array(
    div("row", array(
      div("col-md-3", array(
      heading(_("Numbers"),3),
      table(array("",""), $table_numbers)
      ))
    ))
  ));
}

?>
